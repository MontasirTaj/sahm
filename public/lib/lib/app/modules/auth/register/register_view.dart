import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../../core/theme/app_colors.dart';
import 'register_controller.dart';

class RegisterView extends GetView<RegisterController> {
  const RegisterView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('register'.tr),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: controller.formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const SizedBox(height: 20),

                // Full Name Field
                TextFormField(
                  controller: controller.fullNameController,
                  validator: controller.validateFullName,
                  decoration: InputDecoration(
                    labelText: 'full_name'.tr,
                    prefixIcon: const Icon(Icons.person_outline),
                  ),
                ),

                const SizedBox(height: 16),

                // Email Field
                TextFormField(
                  controller: controller.emailController,
                  keyboardType: TextInputType.emailAddress,
                  validator: controller.validateEmail,
                  decoration: InputDecoration(
                    labelText: 'email'.tr,
                    prefixIcon: const Icon(Icons.email_outlined),
                  ),
                ),

                const SizedBox(height: 16),

                // Phone Field
                TextFormField(
                  controller: controller.phoneController,
                  keyboardType: TextInputType.phone,
                  validator: controller.validatePhone,
                  decoration: InputDecoration(
                    labelText: 'phone'.tr,
                    prefixIcon: const Icon(Icons.phone_outlined),
                  ),
                ),

                const SizedBox(height: 16),

                // Password Field
                Obx(() => TextFormField(
                  controller: controller.passwordController,
                  obscureText: !controller.isPasswordVisible.value,
                  validator: controller.validatePassword,
                  decoration: InputDecoration(
                    labelText: 'password'.tr,
                    prefixIcon: const Icon(Icons.lock_outlined),
                    suffixIcon: IconButton(
                      icon: Icon(
                        controller.isPasswordVisible.value
                            ? Icons.visibility_off_outlined
                            : Icons.visibility_outlined,
                      ),
                      onPressed: controller.togglePasswordVisibility,
                    ),
                  ),
                )),

                const SizedBox(height: 16),

                // Confirm Password Field
                Obx(() => TextFormField(
                  controller: controller.confirmPasswordController,
                  obscureText: !controller.isConfirmPasswordVisible.value,
                  validator: controller.validateConfirmPassword,
                  decoration: InputDecoration(
                    labelText: 'confirm_password'.tr,
                    prefixIcon: const Icon(Icons.lock_outlined),
                    suffixIcon: IconButton(
                      icon: Icon(
                        controller.isConfirmPasswordVisible.value
                            ? Icons.visibility_off_outlined
                            : Icons.visibility_outlined,
                      ),
                      onPressed: controller.toggleConfirmPasswordVisibility,
                    ),
                  ),
                )),

                const SizedBox(height: 32),

                // Register Button
                Obx(() => ElevatedButton(
                  onPressed: controller.isLoading.value ? null : controller.register,
                  child: controller.isLoading.value
                      ? const SizedBox(
                          height: 20,
                          width: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Colors.white,
                          ),
                        )
                      : Text('register'.tr),
                )),

                const SizedBox(height: 24),

                // Login Link
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'already_have_account'.tr,
                      style: const TextStyle(
                        color: AppColors.textSecondary,
                      ),
                    ),
                    TextButton(
                      onPressed: controller.goToLogin,
                      child: Text('login'.tr),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
