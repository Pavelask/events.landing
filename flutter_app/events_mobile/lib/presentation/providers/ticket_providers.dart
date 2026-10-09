import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/di/providers.dart';
import '../../data/models/ticket.dart';

final ticketProvider =
    FutureProvider.autoDispose.family<Ticket, String>((ref, token) async {
  return ref.watch(ticketRepositoryProvider).show(token);
});
