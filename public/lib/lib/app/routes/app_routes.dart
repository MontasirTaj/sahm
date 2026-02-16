part of 'app_pages.dart';

abstract class Routes {
  Routes._();

  static const ONBOARDING = _Paths.ONBOARDING;
  static const LOGIN = _Paths.LOGIN;
  static const REGISTER = _Paths.REGISTER;
  static const MAIN_NAVIGATION = _Paths.MAIN_NAVIGATION;
  static const HOME = _Paths.HOME;
  static const MARKETPLACE = _Paths.MARKETPLACE;
  static const PORTFOLIO = _Paths.PORTFOLIO;
  static const TRANSACTIONS = _Paths.TRANSACTIONS;
  static const PROFILE = _Paths.PROFILE;
  static const PROPERTY_DETAILS = _Paths.PROPERTY_DETAILS;
}

abstract class _Paths {
  _Paths._();

  static const ONBOARDING = '/onboarding';
  static const LOGIN = '/login';
  static const REGISTER = '/register';
  static const MAIN_NAVIGATION = '/main';
  static const HOME = '/home';
  static const MARKETPLACE = '/marketplace';
  static const PORTFOLIO = '/portfolio';
  static const TRANSACTIONS = '/transactions';
  static const PROFILE = '/profile';
  static const PROPERTY_DETAILS = '/property-details';
}
