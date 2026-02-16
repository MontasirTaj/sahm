import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:introduction_screen/introduction_screen.dart';
import '../../../core/theme/app_colors.dart';
import 'onboarding_controller.dart';

class OnboardingView extends GetView<OnboardingController> {
  const OnboardingView({super.key});

  @override
  Widget build(BuildContext context) {
    return IntroductionScreen(
      pages: [
        PageViewModel(
          title: 'استثمر في العقارات بسهولة',
          body: 'ابدأ الاستثمار في العقارات من خلال شراء أسهم جزئية بأقل المبالغ',
          image: _buildImage(Icons.home_work, AppColors.primary),
          decoration: _getPageDecoration(),
        ),
        PageViewModel(
          title: 'تنوع محفظتك الاستثمارية',
          body: 'استثمر في عقارات متنوعة (سكنية، تجارية، صناعية) لتقليل المخاطر',
          image: _buildImage(Icons.pie_chart, AppColors.secondary),
          decoration: _getPageDecoration(),
        ),
        PageViewModel(
          title: 'عوائد دورية مضمونة',
          body: 'احصل على عوائد دورية من الإيجارات وزيادة قيمة العقار',
          image: _buildImage(Icons.trending_up, AppColors.success),
          decoration: _getPageDecoration(),
        ),
        PageViewModel(
          title: 'تداول الأسهم بسهولة',
          body: 'اشترِ وبع أسهمك في السوق الثانوي بكل مرونة وشفافية',
          image: _buildImage(Icons.swap_horiz, AppColors.primary),
          decoration: _getPageDecoration(),
        ),
      ],
      onDone: controller.completeOnboarding,
      onSkip: controller.skipOnboarding,
      showSkipButton: true,
      skip: Text('تخطي', style: TextStyle(color: AppColors.primary)),
      next: Icon(Icons.arrow_forward, color: AppColors.primary),
      done: Text('ابدأ الآن', style: TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary)),
      dotsDecorator: DotsDecorator(
        size: const Size.square(10.0),
        activeSize: const Size(20.0, 10.0),
        activeColor: AppColors.primary,
        color: AppColors.border,
        spacing: const EdgeInsets.symmetric(horizontal: 3.0),
        activeShape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(25.0),
        ),
      ),
    );
  }

  Widget _buildImage(IconData icon, Color color) {
    return Center(
      child: Container(
        width: 200,
        height: 200,
        decoration: BoxDecoration(
          color: color.withOpacity(0.1),
          shape: BoxShape.circle,
        ),
        child: Icon(
          icon,
          size: 100,
          color: color,
        ),
      ),
    );
  }

  PageDecoration _getPageDecoration() {
    return PageDecoration(
      titleTextStyle: const TextStyle(
        fontSize: 28,
        fontWeight: FontWeight.bold,
        color: AppColors.textPrimary,
      ),
      bodyTextStyle: const TextStyle(
        fontSize: 16,
        color: AppColors.textSecondary,
      ),
      imagePadding: const EdgeInsets.only(top: 60, bottom: 40),
      pageColor: Colors.white,
      contentMargin: const EdgeInsets.symmetric(horizontal: 16),
    );
  }
}
