import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../core/theme/app_colors.dart';
import '../../core/utils/date_utils.dart';
import '../../core/utils/html_utils.dart';
import '../../data/models/event.dart';
import '../../data/models/event_document.dart';
import '../../data/models/faq.dart';
import '../providers/events_providers.dart';
import '../widgets/async_value_view.dart';
import '../widgets/remote_image.dart';

class EventDetailScreen extends ConsumerWidget {
  const EventDetailScreen({super.key, required this.slug});

  final String slug;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final event = ref.watch(eventProvider(slug));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Мероприятие'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.pop(),
        ),
      ),
      body: AsyncValueView(
        value: event,
        onRetry: () => ref.invalidate(eventProvider(slug)),
        builder: (data) => _DetailBody(event: data),
      ),
    );
  }
}

class _DetailBody extends StatelessWidget {
  const _DetailBody({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 6,
      child: NestedScrollView(
        headerSliverBuilder: (_, _) => [
          SliverToBoxAdapter(child: _Header(event: event)),
          SliverPersistentHeader(
            pinned: true,
            delegate: _TabBarDelegate(
              const TabBar(
                isScrollable: true,
                labelColor: AppColors.rose,
                unselectedLabelColor: AppColors.inkMuted,
                indicatorColor: AppColors.rose,
                tabs: [
                  Tab(text: 'О событии'),
                  Tab(text: 'Расписание'),
                  Tab(text: 'Спикеры'),
                  Tab(text: 'FAQ'),
                  Tab(text: 'Документы'),
                  Tab(text: 'Галерея'),
                ],
              ),
            ),
          ),
        ],
        body: TabBarView(
          children: [
            _AboutTab(event: event),
            _ScheduleTab(event: event),
            _PeopleTab(event: event),
            _FaqTab(items: event.faqs),
            _DocumentsTab(items: event.documents),
            _GalleryTab(event: event),
          ],
        ),
      ),
    );
  }
}

class _Header extends StatelessWidget {
  const _Header({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        RemoteImage(
          url: event.posterImage ?? event.logo,
          width: double.infinity,
          height: 200,
        ),
        Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                event.title,
                style: const TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const SizedBox(height: 8),
              _InfoRow(
                icon: Icons.calendar_today_outlined,
                text: formatEventDates(event),
              ),
              if ((event.venueName ?? '').isNotEmpty)
                _InfoRow(
                  icon: Icons.place_outlined,
                  text: [event.venueName, event.venueAddress]
                      .whereType<String>()
                      .join(', '),
                ),
            ],
          ),
        ),
      ],
    );
  }
}

class _AboutTab extends StatelessWidget {
  const _AboutTab({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    final description = stripHtml(event.description);

    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        if (description.isEmpty)
          const Text(
            'Описание появится позже.',
            style: TextStyle(color: AppColors.inkMuted),
          )
        else
          Text(description, style: const TextStyle(fontSize: 15, height: 1.5)),
        if ((event.videoUrl ?? '').isNotEmpty) ...[
          const SizedBox(height: 20),
          FilledButton.icon(
            onPressed: () => openExternal(event.videoUrl!),
            icon: const Icon(Icons.play_circle_outline),
            label: const Text('Смотреть видео'),
            style: FilledButton.styleFrom(backgroundColor: AppColors.rose),
          ),
        ],
      ],
    );
  }
}

class _ScheduleTab extends StatelessWidget {
  const _ScheduleTab({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    if (event.days.isEmpty) return const _Empty('Расписание скоро появится');

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: event.days.length,
      itemBuilder: (context, index) {
        final day = event.days[index];
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(
                day.label ?? formatIsoDate(day.date?.toIso8601String()),
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: AppColors.roseDark,
                ),
              ),
            ),
            ...day.events.map((e) {
              final subtitle = [
                if ((e.speaker?.name ?? '').isNotEmpty) e.speaker!.name,
                if ((e.location ?? '').isNotEmpty) e.location,
              ].whereType<String>().join(' · ');

              return Card(
                margin: const EdgeInsets.only(bottom: 10),
                child: ListTile(
                  leading: SizedBox(
                    width: 52,
                    child: Text(
                      e.startTime ?? '',
                      style: const TextStyle(
                        fontWeight: FontWeight.w700,
                        color: AppColors.rose,
                      ),
                    ),
                  ),
                  title: Text(e.title ?? 'Событие'),
                  subtitle: subtitle.isEmpty ? null : Text(subtitle),
                ),
              );
            }),
            const SizedBox(height: 8),
          ],
        );
      },
    );
  }
}

class _PeopleTab extends StatelessWidget {
  const _PeopleTab({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    if (event.speakers.isEmpty && event.guests.isEmpty) {
      return const _Empty('Информация о спикерах скоро появится');
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        if (event.speakers.isNotEmpty) ...[
          const _SectionTitle('Спикеры'),
          ...event.speakers.map(
            (s) => _PersonTile(
              name: s.name,
              subtitle: s.position,
              org: s.organization,
              photo: s.photo,
              description: s.description,
            ),
          ),
        ],
        if (event.guests.isNotEmpty) ...[
          const _SectionTitle('Гости'),
          ...event.guests.map(
            (g) => _PersonTile(
              name: g.name,
              subtitle: g.position,
              org: g.organization,
              photo: g.photo,
              description: g.description,
            ),
          ),
        ],
      ],
    );
  }
}

class _FaqTab extends StatelessWidget {
  const _FaqTab({required this.items});

  final List<Faq> items;

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) return const _Empty('Вопросов пока нет');

    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      separatorBuilder: (_, _) => const SizedBox(height: 8),
      itemBuilder: (context, index) {
        final faq = items[index];
        return Card(
          child: ExpansionTile(
            title: Text(
              faq.question,
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
            childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
            children: [
              Align(
                alignment: Alignment.centerLeft,
                child: Text(
                  stripHtml(faq.answer),
                  style: const TextStyle(height: 1.4),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

class _DocumentsTab extends StatelessWidget {
  const _DocumentsTab({required this.items});

  final List<EventDocument> items;

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) return const _Empty('Документы скоро появятся');

    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      separatorBuilder: (_, _) => const SizedBox(height: 8),
      itemBuilder: (context, index) {
        final doc = items[index];
        return Card(
          child: ListTile(
            leading: const Icon(Icons.description_outlined, color: AppColors.rose),
            title: Text(doc.title),
            trailing: const Icon(Icons.open_in_new),
            onTap: (doc.filePath ?? '').isEmpty
                ? null
                : () => openExternal(doc.filePath!),
          ),
        );
      },
    );
  }
}

class _GalleryTab extends StatelessWidget {
  const _GalleryTab({required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    final hasExternal =
        event.isGalleryExternalVisible && (event.galleryExternalUrl ?? '').isNotEmpty;

    if (event.gallery.isEmpty && !hasExternal) {
      return const _Empty('Галерея скоро появится');
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        if (event.gallery.isNotEmpty)
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: event.gallery.length,
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 3,
              mainAxisSpacing: 8,
              crossAxisSpacing: 8,
            ),
            itemBuilder: (context, index) => RemoteImage(
              url: event.gallery[index],
              width: double.infinity,
              height: 120,
              radius: 8,
            ),
          ),
        if (hasExternal) ...[
          const SizedBox(height: 20),
          Card(
            child: ListTile(
              leading: const Icon(Icons.collections, color: AppColors.rose),
              title: const Text('Внешняя галерея'),
              subtitle: const Text('Больше фото на внешнем ресурсе'),
              trailing: const Icon(Icons.open_in_new),
              onTap: () => openExternal(event.galleryExternalUrl!),
            ),
          ),
        ],
      ],
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({required this.icon, required this.text});

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    if (text.isEmpty) return const SizedBox.shrink();
    return Padding(
      padding: const EdgeInsets.only(top: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 18, color: AppColors.rose),
          const SizedBox(width: 8),
          Expanded(
            child: Text(text, style: const TextStyle(color: AppColors.inkMuted)),
          ),
        ],
      ),
    );
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle(this.title);

  final String title;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: AppColors.roseDark,
        ),
      ),
    );
  }
}

class _PersonTile extends StatelessWidget {
  const _PersonTile({
    required this.name,
    this.subtitle,
    this.org,
    this.photo,
    this.description,
  });

  final String name;
  final String? subtitle;
  final String? org;
  final String? photo;
  final String? description;

  @override
  Widget build(BuildContext context) {
    final sub = [subtitle, org]
        .whereType<String>()
        .where((s) => s.isNotEmpty)
        .join(', ');
    final desc = stripHtml(description);

    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            RemoteImage(url: photo, width: 56, height: 56, radius: 28),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name,
                    style: const TextStyle(
                      fontWeight: FontWeight.w700,
                      fontSize: 16,
                    ),
                  ),
                  if (sub.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Text(
                        sub,
                        style: const TextStyle(color: AppColors.inkMuted),
                      ),
                    ),
                  if (desc.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.only(top: 8),
                      child: Text(
                        desc,
                        style: const TextStyle(height: 1.4, fontSize: 13.5),
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _Empty extends StatelessWidget {
  const _Empty(this.message);

  final String message;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Text(
          message,
          style: const TextStyle(color: AppColors.inkMuted),
        ),
      ),
    );
  }
}

Future<void> openExternal(String url) async {
  final uri = Uri.tryParse(url);
  if (uri == null) return;
  await launchUrl(uri, mode: LaunchMode.externalApplication);
}

class _TabBarDelegate extends SliverPersistentHeaderDelegate {
  _TabBarDelegate(this.tabBar);

  final TabBar tabBar;

  @override
  double get minExtent => tabBar.preferredSize.height;

  @override
  double get maxExtent => tabBar.preferredSize.height;

  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) {
    return Container(
      color: AppColors.surface,
      child: tabBar,
    );
  }

  @override
  bool shouldRebuild(_TabBarDelegate oldDelegate) => false;
}
