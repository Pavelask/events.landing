import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/di/providers.dart';
import '../../data/models/event.dart';

final eventsListProvider = FutureProvider.autoDispose<List<Event>>((ref) async {
  return ref.watch(eventsRepositoryProvider).fetchEvents();
});

final eventProvider =
    FutureProvider.autoDispose.family<Event, String>((ref, slug) async {
  return ref.watch(eventsRepositoryProvider).show(slug);
});
