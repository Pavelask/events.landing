import 'package:intl/intl.dart';

import '../../data/models/event.dart';

String formatEventDates(Event event) {
  final start = event.startDate;
  final end = event.endDate;
  if (start == null) return '';

  final df = DateFormat('d MMMM', 'ru');
  if (end == null || _isSameDay(start, end)) {
    return df.format(start);
  }
  return '${df.format(start)} — ${df.format(end)}';
}

String formatIsoDate(String? iso) {
  if (iso == null) return '';
  final date = DateTime.tryParse(iso);
  if (date == null) return iso;
  return DateFormat('d MMMM yyyy', 'ru').format(date);
}

String formatIsoDateShort(String? iso) {
  if (iso == null) return '';
  final date = DateTime.tryParse(iso);
  if (date == null) return iso;
  return DateFormat('d MMMM', 'ru').format(date);
}

bool _isSameDay(DateTime a, DateTime b) =>
    a.year == b.year && a.month == b.month && a.day == b.day;
