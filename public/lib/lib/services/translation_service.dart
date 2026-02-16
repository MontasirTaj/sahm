import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';

class TranslationService extends GetxService {
  final _box = GetStorage();
  final _key = 'language';

  Locale getLocale() {
    String? langCode = _box.read(_key);
    if (langCode == null) {
      return const Locale('ar', 'SA'); // Default Arabic
    }
    return Locale(langCode.split('_')[0], langCode.split('_')[1]);
  }

  void changeLocale(String langCode) {
    Locale locale = Locale(langCode.split('_')[0], langCode.split('_')[1]);
    _box.write(_key, langCode);
    Get.updateLocale(locale);
  }

  bool isArabic() {
    return getLocale().languageCode == 'ar';
  }
}
