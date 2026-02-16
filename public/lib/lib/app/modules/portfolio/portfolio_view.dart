import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:animate_do/animate_do.dart';
import '../../../core/theme/app_colors.dart';
import '../../widgets/portfolio_card.dart';
import '../../widgets/shimmer_loading.dart';
import '../../widgets/empty_state.dart';
import 'portfolio_controller.dart';

class PortfolioView extends GetView<PortfolioController> {
  const PortfolioView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('my_portfolio'.tr),
      ),
      body: Obx(() {
        if (controller.isLoading.value && controller.portfolioItems.isEmpty) {
          return _buildShimmerLoading();
        }

        if (controller.portfolioItems.isEmpty) {
          return EmptyState(
            icon: Icons.pie_chart_outline,
            title: 'لا توجد استثمارات',
            message: 'لم تقم بأي استثمارات بعد. ابدأ الاستثمار في العقارات الآن!',
            actionText: 'استكشف العقارات',
            onAction: () => Get.back(),
          );
        }

        return RefreshIndicator(
          onRefresh: controller.refresh,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FadeInDown(
                  duration: const Duration(milliseconds: 500),
                  child: _buildPortfolioSummary(),
                ),
                const SizedBox(height: 20),
                FadeInUp(
                  duration: const Duration(milliseconds: 600),
                  child: _buildPerformanceChart(),
                ),
                const SizedBox(height: 20),
                _buildPropertiesList(),
                const SizedBox(height: 20),
              ],
            ),
          ),
        );
      }),
    );
  }

  Widget _buildShimmerLoading() {
    return SingleChildScrollView(
      child: Column(
        children: [
          Container(
            margin: const EdgeInsets.all(16),
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Column(
              children: [
                ShimmerLoading(width: 200, height: 24),
                const SizedBox(height: 12),
                ShimmerLoading(width: 150, height: 32),
              ],
            ),
          ),
          const SizedBox(height: 20),
          Container(
            margin: const EdgeInsets.all(16),
            height: 200,
            child: ShimmerLoading(width: double.infinity, height: 200),
          ),
          ...List.generate(3, (index) => const PortfolioCardShimmer()),
        ],
      ),
    );
  }

  Widget _buildPortfolioSummary() {
    final formatter = NumberFormat('#,##0', 'ar');

    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'portfolio_value'.tr,
            style: const TextStyle(
              color: AppColors.textWhite,
              fontSize: 14,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            '${formatter.format(controller.totalPortfolioValue.value)} ر.س',
            style: const TextStyle(
              color: AppColors.textWhite,
              fontSize: 32,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: _buildSummaryItem(
                  'total_invested'.tr,
                  '${formatter.format(controller.totalInvested.value)} ر.س',
                ),
              ),
              Expanded(
                child: _buildSummaryItem(
                  'profit_loss'.tr,
                  '${controller.totalProfitLoss.value >= 0 ? '+' : ''}${formatter.format(controller.totalProfitLoss.value)} ر.س',
                  color: controller.totalProfitLoss.value >= 0
                      ? AppColors.success
                      : AppColors.error,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: controller.totalProfitLoss.value >= 0
                  ? AppColors.success.withOpacity(0.2)
                  : AppColors.error.withOpacity(0.2),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  controller.totalProfitLoss.value >= 0
                      ? Icons.trending_up
                      : Icons.trending_down,
                  color: controller.totalProfitLoss.value >= 0
                      ? AppColors.success
                      : AppColors.error,
                  size: 20,
                ),
                const SizedBox(width: 8),
                Text(
                  '${controller.profitPercentage.value >= 0 ? '+' : ''}${controller.profitPercentage.value.toStringAsFixed(2)}%',
                  style: TextStyle(
                    color: controller.totalProfitLoss.value >= 0
                        ? AppColors.success
                        : AppColors.error,
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryItem(String label, String value, {Color? color}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            color: AppColors.textWhite,
            fontSize: 12,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: TextStyle(
            color: color ?? AppColors.textWhite,
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }

  Widget _buildPerformanceChart() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.cardBackground,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'performance'.tr,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 20),
          SizedBox(
            height: 200,
            child: controller.portfolioItems.isEmpty
                ? const Center(child: Text('لا توجد بيانات لعرضها'))
                : PieChart(
                    PieChartData(
                      sectionsSpace: 2,
                      centerSpaceRadius: 60,
                      sections: _buildPieChartSections(),
                    ),
                  ),
          ),
        ],
      ),
    );
  }

  List<PieChartSectionData> _buildPieChartSections() {
    final colors = [
      AppColors.primary,
      AppColors.secondary,
      AppColors.info,
      AppColors.success,
      AppColors.warning,
    ];

    return List.generate(controller.portfolioItems.length, (index) {
      final item = controller.portfolioItems[index];
      final percentage = (item.currentValue / controller.totalPortfolioValue.value) * 100;

      return PieChartSectionData(
        color: colors[index % colors.length],
        value: item.currentValue,
        title: '${percentage.toStringAsFixed(1)}%',
        radius: 50,
        titleStyle: const TextStyle(
          fontSize: 12,
          fontWeight: FontWeight.bold,
          color: Colors.white,
        ),
      );
    });
  }

  Widget _buildPropertiesList() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Text(
            'my_properties'.tr,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        const SizedBox(height: 12),
        Obx(() {
          if (controller.portfolioItems.isEmpty) {
            return const Padding(
              padding: EdgeInsets.all(40),
              child: Center(child: Text('لا توجد استثمارات')),
            );
          }
          return ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: controller.portfolioItems.length,
            separatorBuilder: (context, index) => const SizedBox(height: 16),
            itemBuilder: (context, index) {
              final item = controller.portfolioItems[index];
              return PortfolioCard(item: item);
            },
          );
        }),
      ],
    );
  }
}
