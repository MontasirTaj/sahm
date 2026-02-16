import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/theme/app_colors.dart';
import '../data/models/portfolio_item_model.dart';
import 'package:intl/intl.dart';

class PortfolioCard extends StatelessWidget {
  final PortfolioItemModel item;
  final VoidCallback? onTap;

  const PortfolioCard({
    super.key,
    required this.item,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Row(
                children: [
                  // Property Image
                  ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: CachedNetworkImage(
                      imageUrl: item.propertyImage,
                      width: 80,
                      height: 80,
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                        width: 80,
                        height: 80,
                        color: Colors.grey[300],
                      ),
                      errorWidget: (context, url, error) => Container(
                        width: 80,
                        height: 80,
                        color: Colors.grey[300],
                        child: const Icon(Icons.apartment),
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),
                  // Property Info
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item.propertyName,
                          style: Theme.of(context).textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.w600,
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          '${item.sharesOwned} سهم',
                          style: Theme.of(context).textTheme.bodyMedium,
                        ),
                      ],
                    ),
                  ),
                  // Profit/Loss Badge
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: item.isProfitable
                          ? AppColors.success.withOpacity(0.1)
                          : AppColors.error.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      '${item.profitPercentage > 0 ? '+' : ''}${item.profitPercentage.toStringAsFixed(1)}%',
                      style: TextStyle(
                        color: item.isProfitable ? AppColors.success : AppColors.error,
                        fontWeight: FontWeight.w600,
                        fontSize: 14,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              const Divider(height: 1),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: _ValueItem(
                      label: 'المبلغ المستثمر',
                      value: '${NumberFormat('#,###').format(item.totalInvested)} ر.س',
                    ),
                  ),
                  Expanded(
                    child: _ValueItem(
                      label: 'القيمة الحالية',
                      value: '${NumberFormat('#,###').format(item.currentValue)} ر.س',
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: _ValueItem(
                      label: 'الربح/الخسارة',
                      value: '${item.profitLoss > 0 ? '+' : ''}${NumberFormat('#,###').format(item.profitLoss)} ر.س',
                      valueColor: item.isProfitable ? AppColors.success : AppColors.error,
                    ),
                  ),
                  Expanded(
                    child: _ValueItem(
                      label: 'التوزيعات',
                      value: '${NumberFormat('#,###').format(item.totalDividends)} ر.س',
                      valueColor: AppColors.secondary,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ValueItem extends StatelessWidget {
  final String label;
  final String value;
  final Color? valueColor;

  const _ValueItem({
    required this.label,
    required this.value,
    this.valueColor,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall,
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: Theme.of(context).textTheme.bodyLarge?.copyWith(
            color: valueColor,
            fontWeight: FontWeight.w600,
          ),
        ),
      ],
    );
  }
}
