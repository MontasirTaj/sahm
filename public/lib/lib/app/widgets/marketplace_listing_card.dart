import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/theme/app_colors.dart';
import '../data/models/marketplace_listing_model.dart';
import 'package:intl/intl.dart';
import 'share_market_details_sheet.dart';

class MarketplaceListingCard extends StatelessWidget {
  final MarketplaceListingModel listing;
  final VoidCallback? onTap;
  final VoidCallback? onBuy;
  final VoidCallback? onCancel;
  final bool showActions;

  const MarketplaceListingCard({
    super.key,
    required this.listing,
    this.onTap,
    this.onBuy,
    this.onCancel,
    this.showActions = true,
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
                      imageUrl: listing.propertyImage,
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
                  // Listing Info
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          listing.propertyName,
                          style: Theme.of(context).textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.w600,
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const Icon(Icons.person, size: 14, color: AppColors.textSecondary),
                            const SizedBox(width: 4),
                            Text(
                              listing.sellerName,
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Text(
                          _getTimeAgo(),
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            color: AppColors.textHint,
                          ),
                        ),
                      ],
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
                    child: _InfoItem(
                      label: 'عدد الأسهم',
                      value: '${listing.sharesAvailable}',
                      icon: Icons.ballot,
                    ),
                  ),
                  Expanded(
                    child: _InfoItem(
                      label: 'سعر السهم',
                      value: '${NumberFormat('#,###').format(listing.pricePerShare)} ر.س',
                      icon: Icons.attach_money,
                    ),
                  ),
                  Expanded(
                    child: _InfoItem(
                      label: 'القيمة الإجمالية',
                      value: '${NumberFormat('#,###').format(listing.totalValue)} ر.س',
                      icon: Icons.account_balance_wallet,
                    ),
                  ),
                ],
              ),
              if (showActions && listing.isActive) ...[
                const SizedBox(height: 16),
                Row(
                  children: [
                    // Details button
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: () {
                          showModalBottomSheet(
                            context: context,
                            isScrollControlled: true,
                            backgroundColor: Colors.transparent,
                            builder: (context) => ShareMarketDetailsSheet(
                              propertyId: listing.propertyId,
                              propertyName: listing.propertyName,
                              propertyImage: listing.propertyImage,
                            ),
                          );
                        },
                        icon: const Icon(Icons.show_chart, size: 18),
                        label: const Text('تفاصيل السوق'),
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    if (onBuy != null)
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: onBuy,
                          icon: const Icon(Icons.shopping_cart, size: 18),
                          label: const Text('شراء'),
                          style: ElevatedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                          ),
                        ),
                      ),
                    if (onCancel != null)
                      Expanded(
                        child: OutlinedButton.icon(
                          onPressed: onCancel,
                          icon: const Icon(Icons.cancel_outlined, size: 18),
                          label: const Text('إلغاء'),
                          style: OutlinedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            foregroundColor: AppColors.error,
                            side: const BorderSide(color: AppColors.error),
                          ),
                        ),
                      ),
                  ],
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  String _getTimeAgo() {
    final now = DateTime.now();
    final difference = now.difference(listing.createdAt);

    if (difference.inDays > 0) {
      return 'منذ ${difference.inDays} ${difference.inDays == 1 ? 'يوم' : 'أيام'}';
    } else if (difference.inHours > 0) {
      return 'منذ ${difference.inHours} ${difference.inHours == 1 ? 'ساعة' : 'ساعات'}';
    } else if (difference.inMinutes > 0) {
      return 'منذ ${difference.inMinutes} ${difference.inMinutes == 1 ? 'دقيقة' : 'دقائق'}';
    } else {
      return 'الآن';
    }
  }
}

class _InfoItem extends StatelessWidget {
  final String label;
  final String value;
  final IconData icon;

  const _InfoItem({
    required this.label,
    required this.value,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Icon(icon, size: 20, color: AppColors.primary),
        const SizedBox(height: 4),
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall,
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            fontWeight: FontWeight.w600,
          ),
          textAlign: TextAlign.center,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
  }
}
