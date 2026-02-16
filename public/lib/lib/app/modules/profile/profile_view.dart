import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../core/theme/app_colors.dart';
import 'profile_controller.dart';

class ProfileView extends GetView<ProfileController> {
  const ProfileView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('profile'.tr),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildProfileHeader(),
            const SizedBox(height: 20),
            _buildMenuSection(),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileHeader() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: const BorderRadius.only(
          bottomLeft: Radius.circular(30),
          bottomRight: Radius.circular(30),
        ),
      ),
      child: Column(
        children: [
          Stack(
            children: [
              CircleAvatar(
                radius: 50,
                backgroundColor: Colors.white,
                child: Icon(
                  Icons.person,
                  size: 50,
                  color: AppColors.primary,
                ),
              ),
              Positioned(
                bottom: 0,
                right: 0,
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.camera_alt,
                    size: 20,
                    color: AppColors.primary,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Obx(() => Text(
                controller.userName.value,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                ),
              )),
          const SizedBox(height: 4),
          Obx(() => Text(
                controller.userEmail.value,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 14,
                ),
              )),
          const SizedBox(height: 12),
          Obx(() => Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 8,
                ),
                decoration: BoxDecoration(
                  color: controller.isVerified.value
                      ? AppColors.success.withOpacity(0.2)
                      : AppColors.warning.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(
                      controller.isVerified.value
                          ? Icons.verified
                          : Icons.warning,
                      color: Colors.white,
                      size: 16,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      controller.isVerified.value
                          ? 'verified'.tr
                          : 'not_verified'.tr,
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              )),
        ],
      ),
    );
  }

  Widget _buildMenuSection() {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          _buildMenuCard(
            'personal_info'.tr,
            [
              _buildMenuItem(
                Icons.edit_outlined,
                'Edit Profile',
                onTap: controller.editProfile,
              ),
              _buildMenuItem(
                Icons.lock_outlined,
                'change_password'.tr,
                onTap: controller.changePassword,
              ),
              _buildMenuItem(
                Icons.verified_user_outlined,
                'verify_identity'.tr,
                onTap: controller.verifyIdentity,
              ),
            ],
          ),
          const SizedBox(height: 16),
          _buildMenuCard(
            'account_settings'.tr,
            [
              _buildLanguageMenuItem(),
              _buildThemeMenuItem(),
              _buildMenuItem(
                Icons.notifications_outlined,
                'notifications'.tr, onTap: () {
                  Get.snackbar('قريباً', 'سيتم إضافة الإشعارات قريباً');
                },
                showComingSoon: true,
              ),
            ],
          ),
          const SizedBox(height: 16),
          _buildMenuCard(
            'help_support'.tr,
            [
              _buildMenuItem(
                Icons.privacy_tip_outlined,
                'privacy_policy'.tr,
                onTap: () {
                  Get.snackbar('قريباً', 'سيتم إضافة سياسة الخصوصية قريباً');
                },
                showComingSoon: true,
              ),
              _buildMenuItem(
                Icons.description_outlined,
                'terms_conditions'.tr,
                onTap: () {
                  Get.snackbar('قريباً', 'سيتم إضافة الشروط والأحكام قريباً');
                },
                showComingSoon: true,
              ),
              _buildMenuItem(
                Icons.help_outline,
                'contact_us'.tr,
                onTap: () {
                  Get.snackbar('قريباً', 'سيتم إضافة الدعم الفني قريباً');
                },
                showComingSoon: true,
              ),
            ],
          ),
          const SizedBox(height: 16),
          _buildLogoutButton(),
          const SizedBox(height: 20),
        ],
      ),
    );
  }

  Widget _buildMenuCard(String title, List<Widget> items) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppColors.textSecondary,
              ),
            ),
            const SizedBox(height: 8),
            const Divider(),
            ...items,
          ],
        ),
      ),
    );
  }

  Widget _buildMenuItem(
    IconData icon,
    String title, {
    VoidCallback? onTap,
    Widget? trailing,
    bool showComingSoon = false,
  }) {
    return ListTile(
      contentPadding: EdgeInsets.zero,
      leading: Icon(icon, color: AppColors.primary),
      title: Row(
        children: [
          Text(title),
          if (showComingSoon) ...[
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: AppColors.secondary.withOpacity(0.2),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Text(
                'قريباً',
                style: TextStyle(
                  fontSize: 10,
                  color: AppColors.secondary,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ],
        ],
      ),
      trailing: trailing ?? const Icon(Icons.chevron_right),
      onTap: onTap,
    );
  }

  Widget _buildLanguageMenuItem() {
    return ListTile(
      contentPadding: EdgeInsets.zero,
      leading: const Icon(Icons.language, color: AppColors.primary),
      title: Text('language'.tr),
      trailing: DropdownButton<String>(
        value: Get.locale?.languageCode == 'ar' ? 'ar_SA' : 'en_US',
        underline: const SizedBox(),
        items: const [
          DropdownMenuItem(value: 'ar_SA', child: Text('العربية')),
          DropdownMenuItem(value: 'en_US', child: Text('English')),
        ],
        onChanged: (value) {
          if (value != null) {
            controller.changeLanguage(value);
          }
        },
      ),
    );
  }

  Widget _buildThemeMenuItem() {
    return Obx(() => SwitchListTile(
          contentPadding: EdgeInsets.zero,
          secondary: const Icon(Icons.dark_mode_outlined, color: AppColors.primary),
          title: Text('dark_mode'.tr),
          value: controller.themeService.isSavedDarkMode(),
          onChanged: (value) {
            controller.toggleTheme();
          },
        ));
  }

  Widget _buildLogoutButton() {
    return SizedBox(
      width: double.infinity,
      child: OutlinedButton.icon(
        onPressed: () {
          Get.dialog(
            AlertDialog(
              title: Text('logout'.tr),
              content: const Text('Are you sure you want to logout?'),
              actions: [
                TextButton(
                  onPressed: () => Get.back(),
                  child: Text('cancel'.tr),
                ),
                ElevatedButton(
                  onPressed: () {
                    Get.back();
                    controller.logout();
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.error,
                  ),
                  child: Text('logout'.tr),
                ),
              ],
            ),
          );
        },
        icon: const Icon(Icons.logout),
        label: Text('logout'.tr),
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.error,
          side: const BorderSide(color: AppColors.error),
          padding: const EdgeInsets.symmetric(vertical: 16),
        ),
      ),
    );
  }
}
