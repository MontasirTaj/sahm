import 'package:get/get.dart';
import '../modules/home/home_binding.dart';
import '../modules/home/home_view.dart';
import '../modules/auth/login/login_binding.dart';
import '../modules/auth/login/login_view.dart';
import '../modules/auth/register/register_binding.dart';
import '../modules/auth/register/register_view.dart';
import '../modules/marketplace/marketplace_binding.dart';
import '../modules/marketplace/marketplace_view.dart';
import '../modules/portfolio/portfolio_binding.dart';
import '../modules/portfolio/portfolio_view.dart';
import '../modules/transactions/transactions_binding.dart';
import '../modules/transactions/transactions_view.dart';
import '../modules/profile/profile_binding.dart';
import '../modules/profile/profile_view.dart';
import '../modules/property_details/property_details_binding.dart';
import '../modules/property_details/property_details_view.dart';
import '../modules/main_navigation/main_navigation_binding.dart';
import '../modules/main_navigation/main_navigation_view.dart';
import '../modules/onboarding/onboarding_binding.dart';
import '../modules/onboarding/onboarding_view.dart';
import '../modules/onboarding/onboarding_controller.dart';

part 'app_routes.dart';

class AppPages {
  AppPages._();

  // Check if onboarding should be shown
  static String get INITIAL =>
    OnboardingController.shouldShowOnboarding()
      ? Routes.ONBOARDING
      : Routes.MAIN_NAVIGATION;

  static final routes = [
    GetPage(
      name: _Paths.ONBOARDING,
      page: () => const OnboardingView(),
      binding: OnboardingBinding(),
    ),
    GetPage(
      name: _Paths.LOGIN,
      page: () => const LoginView(),
      binding: LoginBinding(),
    ),
    GetPage(
      name: _Paths.REGISTER,
      page: () => const RegisterView(),
      binding: RegisterBinding(),
    ),
    GetPage(
      name: _Paths.MAIN_NAVIGATION,
      page: () => const MainNavigationView(),
      binding: MainNavigationBinding(),
    ),
    GetPage(
      name: _Paths.HOME,
      page: () => const HomeView(),
      binding: HomeBinding(),
    ),
    GetPage(
      name: _Paths.MARKETPLACE,
      page: () => const MarketplaceView(),
      binding: MarketplaceBinding(),
    ),
    GetPage(
      name: _Paths.PORTFOLIO,
      page: () => const PortfolioView(),
      binding: PortfolioBinding(),
    ),
    GetPage(
      name: _Paths.TRANSACTIONS,
      page: () => const TransactionsView(),
      binding: TransactionsBinding(),
    ),
    GetPage(
      name: _Paths.PROFILE,
      page: () => const ProfileView(),
      binding: ProfileBinding(),
    ),
    GetPage(
      name: _Paths.PROPERTY_DETAILS,
      page: () => const PropertyDetailsView(),
      binding: PropertyDetailsBinding(),
    ),
  ];
}
