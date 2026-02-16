import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';

class ThemeService extends GetxService {
  final _box = GetStorage();
  final _key = 'isDarkMode';

  // Make it observable
  final isDarkMode = false.obs;

  @override
  void onInit() {
    super.onInit();
    // Load saved theme on init
    isDarkMode.value = _box.read(_key) ?? false;
  }

  ThemeMode getThemeMode() {
    return isDarkMode.value ? ThemeMode.dark : ThemeMode.light;
  }

  bool isSavedDarkMode() {
    return isDarkMode.value;
  }

  void saveThemeMode(bool isDark) {
    isDarkMode.value = isDark;
    _box.write(_key, isDark);
  }

  void changeThemeMode() {
    final newMode = !isDarkMode.value;
    saveThemeMode(newMode);
    Get.changeThemeMode(newMode ? ThemeMode.dark : ThemeMode.light);
  }
}
