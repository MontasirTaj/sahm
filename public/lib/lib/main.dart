import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'app/routes/app_pages.dart';
import 'app/translations/app_translations.dart';
import 'core/theme/app_theme.dart';
import 'services/theme_service.dart';
import 'services/translation_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await GetStorage.init();

  // Initialize date formatting for Arabic locale
  await initializeDateFormatting('ar', null);
  await initializeDateFormatting('en', null);

  // Initialize services
  Get.put(ThemeService());
  Get.put(TranslationService());

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final themeService = Get.find<ThemeService>();
    final translationService = Get.find<TranslationService>();

    return GetMaterialApp(
      title: 'سهمي - Sahmi',
      debugShowCheckedModeBanner: false,

      // Theme Configuration
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: themeService.getThemeMode(),

      // Translation Configuration
      translations: AppTranslations(),
      locale: translationService.getLocale(),
      fallbackLocale: const Locale('ar', 'SA'),

      // Routing
      initialRoute: AppPages.INITIAL,
      getPages: AppPages.routes,
    );
  }
}
