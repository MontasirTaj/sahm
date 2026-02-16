import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../core/theme/app_colors.dart';

class ShimmerLoading extends StatelessWidget {
  final double width;
  final double height;
  final BorderRadius? borderRadius;

  const ShimmerLoading({
    super.key,
    required this.width,
    required this.height,
    this.borderRadius,
  });

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: AppColors.border.withOpacity(0.3),
      highlightColor: Colors.white.withOpacity(0.8),
      child: Container(
        width: width,
        height: height,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: borderRadius ?? BorderRadius.circular(8),
        ),
      ),
    );
  }
}

class PropertyCardShimmer extends StatelessWidget {
  const PropertyCardShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const ShimmerLoading(
              width: double.infinity,
              height: 180,
              borderRadius: BorderRadius.all(Radius.circular(12)),
            ),
            const SizedBox(height: 12),
            const ShimmerLoading(width: 200, height: 20),
            const SizedBox(height: 8),
            const ShimmerLoading(width: 150, height: 16),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const ShimmerLoading(width: 100, height: 16),
                const ShimmerLoading(width: 80, height: 16),
              ],
            ),
            const SizedBox(height: 12),
            const ShimmerLoading(
              width: double.infinity,
              height: 8,
              borderRadius: BorderRadius.all(Radius.circular(4)),
            ),
          ],
        ),
      ),
    );
  }
}

class MarketplaceListingShimmer extends StatelessWidget {
  const MarketplaceListingShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            const ShimmerLoading(width: 80, height: 80),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const ShimmerLoading(width: double.infinity, height: 18),
                  const SizedBox(height: 8),
                  const ShimmerLoading(width: 120, height: 14),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const ShimmerLoading(width: 80, height: 16),
                      const ShimmerLoading(width: 60, height: 16),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class PortfolioCardShimmer extends StatelessWidget {
  const PortfolioCardShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                const ShimmerLoading(width: 60, height: 60),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const ShimmerLoading(width: double.infinity, height: 18),
                      const SizedBox(height: 8),
                      const ShimmerLoading(width: 100, height: 14),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                Column(
                  children: [
                    const ShimmerLoading(width: 60, height: 14),
                    const SizedBox(height: 4),
                    const ShimmerLoading(width: 80, height: 16),
                  ],
                ),
                Column(
                  children: [
                    const ShimmerLoading(width: 60, height: 14),
                    const SizedBox(height: 4),
                    const ShimmerLoading(width: 80, height: 16),
                  ],
                ),
                Column(
                  children: [
                    const ShimmerLoading(width: 60, height: 14),
                    const SizedBox(height: 4),
                    const ShimmerLoading(width: 80, height: 16),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
