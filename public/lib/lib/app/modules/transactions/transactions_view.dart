import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import '../../../core/theme/app_colors.dart';
import '../../data/models/transaction_model.dart';
import 'transactions_controller.dart';

class TransactionsView extends GetView<TransactionsController> {
  const TransactionsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('transactions'.tr),
      ),
      body: Column(
        children: [
          _buildFilterChips(),
          Expanded(
            child: Obx(() {
              if (controller.isLoading.value) {
                return const Center(child: CircularProgressIndicator());
              }

              final transactions = controller.filteredTransactions;

              if (transactions.isEmpty) {
                return Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.receipt_long_outlined,
                        size: 80,
                        color: AppColors.textSecondary.withOpacity(0.5),
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'no_data'.tr,
                        style: TextStyle(
                          color: AppColors.textSecondary.withOpacity(0.7),
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                );
              }

              return RefreshIndicator(
                onRefresh: controller.onRefresh,
                child: ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: transactions.length,
                  itemBuilder: (context, index) {
                    final transaction = transactions[index];
                    return _buildTransactionCard(transaction);
                  },
                ),
              );
            }),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChips() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        child: Obx(() => Row(
              children: [
                _buildFilterChip('all', 'الكل'),
                const SizedBox(width: 8),
                _buildFilterChip('purchase', 'purchase'.tr),
                const SizedBox(width: 8),
                _buildFilterChip('sale', 'sale'.tr),
                const SizedBox(width: 8),
                _buildFilterChip('dividend', 'dividend'.tr),
                const SizedBox(width: 8),
                _buildFilterChip('deposit', 'deposit'.tr),
                const SizedBox(width: 8),
                _buildFilterChip('withdrawal', 'withdrawal'.tr),
              ],
            )),
      ),
    );
  }

  Widget _buildFilterChip(String value, String label) {
    final isSelected = controller.selectedFilter.value == value;

    return FilterChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (selected) {
        if (selected) {
          controller.setFilter(value);
        }
      },
      backgroundColor: Colors.white,
      selectedColor: AppColors.primary,
      labelStyle: TextStyle(
        color: isSelected ? Colors.white : AppColors.textPrimary,
        fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
      ),
    );
  }

  Widget _buildTransactionCard(TransactionModel transaction) {
    final formatter = NumberFormat('#,##0', 'ar');
    final dateFormatter = DateFormat('dd/MM/yyyy HH:mm', 'ar');

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                _buildTransactionIcon(transaction.type),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        transaction.type.tr,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        transaction.propertyName,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.textSecondary,
                        ),
                      ),
                    ],
                  ),
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      '${_getSign(transaction.type)}${formatter.format(transaction.amount)} ${'currency'.tr}',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: _getAmountColor(transaction.type),
                      ),
                    ),
                    const SizedBox(height: 4),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 8,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: _getStatusColor(transaction.status).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: Text(
                        transaction.status.tr,
                        style: TextStyle(
                          fontSize: 11,
                          fontWeight: FontWeight.w600,
                          color: _getStatusColor(transaction.status),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
            if (transaction.shares != null) ...[
              const SizedBox(height: 12),
              const Divider(),
              const SizedBox(height: 12),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    '${'number_of_shares'.tr}: ${formatter.format(transaction.shares)}',
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  if (transaction.pricePerShare != null)
                    Text(
                      '${'price_per_share'.tr}: ${formatter.format(transaction.pricePerShare)} ${'currency'.tr}',
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.textSecondary,
                      ),
                    ),
                ],
              ),
            ],
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  dateFormatter.format(transaction.createdAt),
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.textHint,
                  ),
                ),
                Text(
                  'ID: ${transaction.id}',
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.textHint,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTransactionIcon(String type) {
    IconData icon;
    Color color;

    switch (type) {
      case 'purchase':
        icon = Icons.shopping_cart;
        color = AppColors.info;
        break;
      case 'sale':
        icon = Icons.sell;
        color = AppColors.warning;
        break;
      case 'dividend':
        icon = Icons.payments;
        color = AppColors.success;
        break;
      case 'deposit':
        icon = Icons.add_circle;
        color = AppColors.success;
        break;
      case 'withdrawal':
        icon = Icons.remove_circle;
        color = AppColors.error;
        break;
      default:
        icon = Icons.swap_horiz;
        color = AppColors.textSecondary;
    }

    return Container(
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Icon(icon, color: color, size: 24),
    );
  }

  String _getSign(String type) {
    if (type == 'purchase' || type == 'withdrawal') {
      return '- ';
    } else if (type == 'sale' || type == 'dividend' || type == 'deposit') {
      return '+ ';
    }
    return '';
  }

  Color _getAmountColor(String type) {
    if (type == 'purchase' || type == 'withdrawal') {
      return AppColors.error;
    } else if (type == 'sale' || type == 'dividend' || type == 'deposit') {
      return AppColors.success;
    }
    return AppColors.textPrimary;
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'completed':
        return AppColors.success;
      case 'pending':
        return AppColors.warning;
      case 'cancelled':
      case 'failed':
        return AppColors.error;
      default:
        return AppColors.textSecondary;
    }
  }
}
