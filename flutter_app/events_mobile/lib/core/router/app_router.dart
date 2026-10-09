import 'package:go_router/go_router.dart';

import '../../presentation/screens/event_detail_screen.dart';
import '../../presentation/screens/home_screen.dart';
import '../../presentation/screens/login_screen.dart';
import '../../presentation/screens/ticket_screen.dart';

final appRouter = GoRouter(
  routes: [
    GoRoute(
      path: '/',
      builder: (_, _) => const HomeScreen(),
    ),
    GoRoute(
      path: '/events/:slug',
      builder: (_, state) =>
          EventDetailScreen(slug: state.pathParameters['slug']!),
    ),
    GoRoute(
      path: '/login',
      builder: (_, state) =>
          LoginScreen(redirect: state.uri.queryParameters['redirect']),
    ),
    GoRoute(
      path: '/ticket/:token',
      builder: (_, state) =>
          TicketScreen(token: state.pathParameters['token']!),
    ),
  ],
);
