import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:fl_chart/fl_chart.dart';
import '../../core/theme/app_colors.dart';
import '../data/models/share_market_data_model.dart';
import '../data/providers/share_market_data_provider.dart';

class ShareMarketDetailsSheet extends StatefulWidget {
  final String propertyId;
  final String propertyName;
  final String propertyImage;

  const ShareMarketDetailsSheet({
    super.key,
    required this.propertyId,
    required this.propertyName,
    required this.propertyImage,
  });

  @override
  State<ShareMarketDetailsSheet> createState() => _ShareMarketDetailsSheetState();
}

class _ShareMarketDetailsSheetState extends State<ShareMarketDetailsSheet> {
  final _provider = ShareMarketDataProvider();
  ShareMarketDataModel? _marketData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadMarketData();
  }

  Future<void> _loadMarketData() async {
    setState(() => _isLoading = true);
    try {
      final data = await _provider.getMarketData(widget.propertyId, widget.propertyName);
      setState(() {
        _marketData = data;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      Get.snackbar('خطأ', 'فشل تحميل بيانات السوق');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.85,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        children: [
          // Handle
          Container(
            margin: const EdgeInsets.only(top: 12, bottom: 8),
            width: 40,
            height: 4,
            decoration: BoxDecoration(
              color: Colors.grey[300],
              borderRadius: BorderRadius.circular(2),
            ),
          ),

          // Header
          _buildHeader(),

          const Divider(height: 1),

          // Content
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _marketData == null
                    ? const Center(child: Text('فشل تحميل البيانات'))
                    : _buildContent(),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader() {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  widget.propertyName,
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                if (_marketData != null) ...[
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Text(
                        '${NumberFormat('#,###').format(_marketData!.currentPrice)} ر.س',
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppColors.primary,
                        ),
                      ),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: _marketData!.priceChange24h >= 0
                              ? AppColors.success.withOpacity(0.1)
                              : AppColors.error.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          '${_marketData!.priceChange24h >= 0 ? '+' : ''}${_marketData!.priceChangePercentage24h.toStringAsFixed(2)}%',
                          style: TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: _marketData!.priceChange24h >= 0
                                ? AppColors.success
                                : AppColors.error,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ],
            ),
          ),
          IconButton(
            onPressed: () => Navigator.pop(context),
            icon: const Icon(Icons.close),
          ),
        ],
      ),
    );
  }

  Widget _buildContent() {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        _buildPriceStats(),
        const SizedBox(height: 20),
        _buildPriceChart(),
        const SizedBox(height: 20),
        _buildOrderBook(),
        const SizedBox(height: 20),
        _buildRecentTransactions(),
      ],
    );
  }

  Widget _buildPriceStats() {
    final formatter = NumberFormat('#,###');
    return Row(
      children: [
        Expanded(
          child: _StatCard(
            label: 'أعلى سعر (7 أيام)',
            value: '${formatter.format(_marketData!.highPrice24h)} ر.س',
            color: AppColors.success,
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: _StatCard(
            label: 'أقل سعر (7 أيام)',
            value: '${formatter.format(_marketData!.lowPrice24h)} ر.س',
            color: AppColors.error,
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: _StatCard(
            label: 'حجم التداول',
            value: '${formatter.format(_marketData!.totalVolume24h)}',
            color: AppColors.info,
          ),
        ),
      ],
    );
  }

  Widget _buildPriceChart() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'تاريخ الأسعار (7 أيام)',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          SizedBox(
            height: 200,
            child: LineChart(
              LineChartData(
                gridData: FlGridData(
                  show: true,
                  drawVerticalLine: false,
                  horizontalInterval: 1,
                  getDrawingHorizontalLine: (value) {
                    return FlLine(
                      color: Colors.grey[300],
                      strokeWidth: 1,
                    );
                  },
                ),
                titlesData: FlTitlesData(
                  show: true,
                  rightTitles: const AxisTitles(
                    sideTitles: SideTitles(showTitles: false),
                  ),
                  topTitles: const AxisTitles(
                    sideTitles: SideTitles(showTitles: false),
                  ),
                  bottomTitles: AxisTitles(
                    sideTitles: SideTitles(
                      showTitles: true,
                      reservedSize: 30,
                      interval: 1,
                      getTitlesWidget: (value, meta) {
                        if (value.toInt() >= 0 && value.toInt() < _marketData!.priceHistory.length) {
                          final date = _marketData!.priceHistory[value.toInt()].timestamp;
                          return Padding(
                            padding: const EdgeInsets.only(top: 8),
                            child: Text(
                              DateFormat('dd/MM').format(date),
                              style: const TextStyle(fontSize: 10),
                            ),
                          );
                        }
                        return const Text('');
                      },
                    ),
                  ),
                  leftTitles: AxisTitles(
                    sideTitles: SideTitles(
                      showTitles: true,
                      reservedSize: 50,
                      getTitlesWidget: (value, meta) {
                        return Text(
                          NumberFormat.compact(locale: 'ar').format(value),
                          style: const TextStyle(fontSize: 10),
                        );
                      },
                    ),
                  ),
                ),
                borderData: FlBorderData(show: false),
                minX: 0,
                maxX: (_marketData!.priceHistory.length - 1).toDouble(),
                minY: _marketData!.lowPrice24h * 0.98,
                maxY: _marketData!.highPrice24h * 1.02,
                lineBarsData: [
                  LineChartBarData(
                    spots: _marketData!.priceHistory.asMap().entries.map((e) {
                      return FlSpot(e.key.toDouble(), e.value.price);
                    }).toList(),
                    isCurved: true,
                    color: AppColors.primary,
                    barWidth: 3,
                    isStrokeCapRound: true,
                    dotData: const FlDotData(show: false),
                    belowBarData: BarAreaData(
                      show: true,
                      color: AppColors.primary.withOpacity(0.1),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildOrderBook() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        children: [
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text(
              'دفتر الطلبات',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      color: AppColors.success.withOpacity(0.1),
                      child: const Center(
                        child: Text(
                          'طلبات الشراء',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: AppColors.success,
                          ),
                        ),
                      ),
                    ),
                    ..._marketData!.buyOrders.take(5).map((order) {
                      return _OrderBookRow(
                        price: order.price,
                        quantity: order.quantity,
                        isBuy: true,
                      );
                    }),
                  ],
                ),
              ),
              Container(width: 1, height: 200, color: Colors.grey[300]),
              Expanded(
                child: Column(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      color: AppColors.error.withOpacity(0.1),
                      child: const Center(
                        child: Text(
                          'طلبات البيع',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: AppColors.error,
                          ),
                        ),
                      ),
                    ),
                    ..._marketData!.sellOrders.take(5).map((order) {
                      return _OrderBookRow(
                        price: order.price,
                        quantity: order.quantity,
                        isBuy: false,
                      );
                    }),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildRecentTransactions() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'آخر المعاملات',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          ..._marketData!.recentTransactions.take(10).map((transaction) {
            return _TransactionRow(transaction: transaction);
          }),
        ],
      ),
    );
  }
}

class _StatCard extends StatelessWidget {
  final String label;
  final String value;
  final Color color;

  const _StatCard({
    required this.label,
    required this.value,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              color: AppColors.textSecondary,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
              color: color,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

class _OrderBookRow extends StatelessWidget {
  final double price;
  final int quantity;
  final bool isBuy;

  const _OrderBookRow({
    required this.price,
    required this.quantity,
    required this.isBuy,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        border: Border(
          bottom: BorderSide(color: Colors.grey[200]!),
        ),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            NumberFormat('#,###').format(price),
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.bold,
              color: isBuy ? AppColors.success : AppColors.error,
            ),
          ),
          Text(
            quantity.toString(),
            style: const TextStyle(fontSize: 12),
          ),
        ],
      ),
    );
  }
}

class _TransactionRow extends StatelessWidget {
  final RecentTransaction transaction;

  const _TransactionRow({required this.transaction});

  @override
  Widget build(BuildContext context) {
    final isBuy = transaction.type == 'buy';
    final formatter = NumberFormat('#,###');

    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8),
      decoration: BoxDecoration(
        border: Border(
          bottom: BorderSide(color: Colors.grey[200]!),
        ),
      ),
      child: Row(
        children: [
          Expanded(
            flex: 2,
            child: Text(
              _getTimeAgo(transaction.timestamp),
              style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
            ),
          ),
          Expanded(
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: isBuy
                    ? AppColors.success.withOpacity(0.1)
                    : AppColors.error.withOpacity(0.1),
                borderRadius: BorderRadius.circular(4),
              ),
              child: Text(
                isBuy ? 'شراء' : 'بيع',
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.bold,
                  color: isBuy ? AppColors.success : AppColors.error,
                ),
                textAlign: TextAlign.center,
              ),
            ),
          ),
          const SizedBox(width: 8),
          Expanded(
            flex: 2,
            child: Text(
              '${formatter.format(transaction.price)} ر.س',
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600),
              textAlign: TextAlign.end,
            ),
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              '${transaction.quantity}',
              style: const TextStyle(fontSize: 12),
              textAlign: TextAlign.end,
            ),
          ),
        ],
      ),
    );
  }

  String _getTimeAgo(DateTime timestamp) {
    final diff = DateTime.now().difference(timestamp);
    if (diff.inMinutes < 1) return 'الآن';
    if (diff.inMinutes < 60) return 'منذ ${diff.inMinutes} د';
    if (diff.inHours < 24) return 'منذ ${diff.inHours} س';
    return 'منذ ${diff.inDays} يوم';
  }
}
