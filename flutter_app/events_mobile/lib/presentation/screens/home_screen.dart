import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/theme/app_colors.dart';
import '../providers/events_providers.dart';
import '../widgets/async_value_view.dart';
import '../widgets/event_card.dart';

class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final events = ref.watch(eventsListProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Мероприятия',
          style: TextStyle(fontWeight: FontWeight.w700),
        ),
      ),
      body: RefreshIndicator(
        color: AppColors.rose,
        onRefresh: () async => ref.invalidate(eventsListProvider),
        child: AsyncValueView(
          value: events,
          onRetry: () => ref.invalidate(eventsListProvider),
          builder: (data) {
            if (data.isEmpty) {
              return ListView(
                children: const [
                  SizedBox(height: 120),
                  Center(
                    child: Text(
                      'Пока нет мероприятий',
                      style: TextStyle(color: AppColors.inkMuted),
                    ),
                  ),
                ],
              );
            }

            return ListView.separated(
              padding: const EdgeInsets.all(16),
              itemCount: data.length,
              separatorBuilder: (_, _) => const SizedBox(height: 16),
              itemBuilder: (context, index) {
                final event = data[index];
                return EventCard(
                  event: event,
                  onTap: () => context.push('/events/${event.slug}'),
                );
              },
            );
          },
        ),
      ),
    );
  }
}
