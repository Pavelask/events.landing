import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/di/providers.dart';
import '../../core/network/api_exception.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/date_utils.dart';
import '../../data/models/ticket.dart';
import '../providers/ticket_providers.dart';
import '../widgets/async_value_view.dart';
import '../widgets/remote_image.dart';

class TicketScreen extends ConsumerStatefulWidget {
  const TicketScreen({super.key, required this.token});

  final String token;

  @override
  ConsumerState<TicketScreen> createState() => _TicketScreenState();
}

class _TicketScreenState extends ConsumerState<TicketScreen> {
  bool _checkingIn = false;
  String? _message;

  Future<void> _checkIn() async {
    setState(() {
      _checkingIn = true;
      _message = null;
    });

    try {
      final result =
          await ref.read(ticketRepositoryProvider).checkIn(widget.token);
      if (!mounted) return;
      ref.invalidate(ticketProvider(widget.token));
      setState(() {
        _message = result.alreadyCheckedIn == true
            ? 'Участник уже был отмечен ранее'
            : 'Участник успешно отмечен';
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      if (e.isUnauthorized) {
        setState(() => _message = 'Требуется вход сотрудника');
        context.push('/login?redirect=/ticket/${widget.token}');
      } else {
        setState(() => _message = e.message);
      }
    } finally {
      if (mounted) setState(() => _checkingIn = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final ticket = ref.watch(ticketProvider(widget.token));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Билет'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.pop(),
        ),
      ),
      body: AsyncValueView(
        value: ticket,
        onRetry: () => ref.invalidate(ticketProvider(widget.token)),
        builder: (data) => _TicketBody(
          ticket: data,
          checkingIn: _checkingIn,
          message: _message,
          onCheckIn: _checkIn,
        ),
      ),
    );
  }
}

class _TicketBody extends StatelessWidget {
  const _TicketBody({
    required this.ticket,
    required this.checkingIn,
    required this.message,
    required this.onCheckIn,
  });

  final Ticket ticket;
  final bool checkingIn;
  final String? message;
  final VoidCallback onCheckIn;

  @override
  Widget build(BuildContext context) {
    final event = ticket.event;
    final dateRange = [
      formatIsoDateShort(event?.startDate?.toIso8601String()),
      formatIsoDateShort(event?.endDate?.toIso8601String()),
    ].where((s) => s.isNotEmpty).join(' — ');

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Card(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              children: [
                RemoteImage(
                  url: ticket.qrUrl,
                  width: 200,
                  height: 200,
                  radius: 12,
                ),
                const SizedBox(height: 16),
                _StatusChip(checkedIn: ticket.isCheckedIn),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  event?.title ?? 'Мероприятие',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                const SizedBox(height: 8),
                _InfoRow(
                  icon: Icons.person_outline,
                  text: ticket.participant?.name ?? 'Участник',
                ),
                if ((ticket.participant?.email ?? '').isNotEmpty)
                  _InfoRow(
                    icon: Icons.email_outlined,
                    text: ticket.participant!.email!,
                  ),
                if (dateRange.isNotEmpty)
                  _InfoRow(
                    icon: Icons.calendar_today_outlined,
                    text: dateRange,
                  ),
                if ((event?.venueName ?? '').isNotEmpty)
                  _InfoRow(
                    icon: Icons.place_outlined,
                    text: event!.venueName!,
                  ),
              ],
            ),
          ),
        ),
        if (message != null) ...[
          const SizedBox(height: 16),
          Card(
            color: AppColors.roseSoft,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Text(
                message!,
                style: const TextStyle(color: AppColors.roseDark),
              ),
            ),
          ),
        ],
        const SizedBox(height: 24),
        FilledButton.icon(
          onPressed: checkingIn ? null : onCheckIn,
          style: FilledButton.styleFrom(
            backgroundColor: AppColors.rose,
            padding: const EdgeInsets.symmetric(vertical: 16),
          ),
          icon: checkingIn
              ? const SizedBox(
                  width: 18,
                  height: 18,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: Colors.white,
                  ),
                )
              : const Icon(Icons.qr_code_scanner),
          label: Text(ticket.isCheckedIn ? 'Отметить повторно' : 'Отметить вход'),
        ),
      ],
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip({required this.checkedIn});

  final bool checkedIn;

  @override
  Widget build(BuildContext context) {
    final color = checkedIn ? Colors.green : AppColors.inkMuted;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(checkedIn ? Icons.check_circle : Icons.schedule,
              size: 16, color: color),
          const SizedBox(width: 6),
          Text(
            checkedIn ? 'Отмечен' : 'Не отмечен',
            style: TextStyle(color: color, fontWeight: FontWeight.w600),
          ),
        ],
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({required this.icon, required this.text});

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 16, color: AppColors.rose),
          const SizedBox(width: 8),
          Expanded(
            child: Text(text, style: const TextStyle(color: AppColors.inkMuted)),
          ),
        ],
      ),
    );
  }
}
