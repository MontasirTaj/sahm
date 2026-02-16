import '../models/share_market_data_model.dart';

class ShareMarketDataProvider {
  Future<ShareMarketDataModel> getMarketData(String propertyId, String propertyName) async {
    await Future.delayed(const Duration(milliseconds: 500));
    return _generateMockMarketData(propertyId, propertyName);
  }

  ShareMarketDataModel _generateMockMarketData(String propertyId, String propertyName) {
    final basePrice = 5000 + (propertyId.hashCode % 10000).toDouble();
    final currentPrice = basePrice + ((-500 + (propertyId.hashCode % 1000)).toDouble());

    // Generate price history for the last 7 days
    final priceHistory = <PriceHistoryPoint>[];
    for (int i = 7; i >= 0; i--) {
      final variance = (-200 + (i * 37 + propertyId.hashCode) % 400).toDouble();
      priceHistory.add(PriceHistoryPoint(
        timestamp: DateTime.now().subtract(Duration(days: i)),
        price: basePrice + variance,
        volume: 10 + (i * 13 + propertyId.hashCode) % 50,
      ));
    }

    // Generate sell orders (asks) - ascending prices
    final sellOrders = <MarketOrder>[];
    int cumulativeSell = 0;
    for (int i = 0; i < 10; i++) {
      final quantity = 5 + (i * 7 + propertyId.hashCode) % 20;
      cumulativeSell += quantity;
      sellOrders.add(MarketOrder(
        price: currentPrice + (i * 50) + 10,
        quantity: quantity,
        cumulativeQuantity: cumulativeSell,
      ));
    }

    // Generate buy orders (bids) - descending prices
    final buyOrders = <MarketOrder>[];
    int cumulativeBuy = 0;
    for (int i = 0; i < 10; i++) {
      final quantity = 5 + (i * 11 + propertyId.hashCode) % 25;
      cumulativeBuy += quantity;
      buyOrders.add(MarketOrder(
        price: currentPrice - (i * 50) - 10,
        quantity: quantity,
        cumulativeQuantity: cumulativeBuy,
      ));
    }

    // Generate recent transactions
    final recentTransactions = <RecentTransaction>[];
    for (int i = 0; i < 20; i++) {
      final minutesAgo = i * 15;
      final isBuy = (i + propertyId.hashCode) % 2 == 0;
      final priceVariance = (-100 + (i * 19 + propertyId.hashCode) % 200).toDouble();
      recentTransactions.add(RecentTransaction(
        timestamp: DateTime.now().subtract(Duration(minutes: minutesAgo)),
        price: currentPrice + priceVariance,
        quantity: 1 + (i * 3 + propertyId.hashCode) % 10,
        type: isBuy ? 'buy' : 'sell',
      ));
    }

    final highPrice = priceHistory.map((e) => e.price).reduce((a, b) => a > b ? a : b);
    final lowPrice = priceHistory.map((e) => e.price).reduce((a, b) => a < b ? a : b);
    final oldestPrice = priceHistory.first.price;
    final priceChange = currentPrice - oldestPrice;
    final priceChangePercentage = (priceChange / oldestPrice) * 100;
    final totalVolume = priceHistory.map((e) => e.volume).reduce((a, b) => a + b);

    return ShareMarketDataModel(
      propertyId: propertyId,
      propertyName: propertyName,
      currentPrice: currentPrice,
      highPrice24h: highPrice,
      lowPrice24h: lowPrice,
      priceChange24h: priceChange,
      priceChangePercentage24h: priceChangePercentage,
      totalVolume24h: totalVolume,
      priceHistory: priceHistory,
      sellOrders: sellOrders,
      buyOrders: buyOrders,
      recentTransactions: recentTransactions,
    );
  }
}
