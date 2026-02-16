class ShareMarketDataModel {
  final String propertyId;
  final String propertyName;
  final double currentPrice;
  final double highPrice24h;
  final double lowPrice24h;
  final double priceChange24h;
  final double priceChangePercentage24h;
  final int totalVolume24h;
  final List<PriceHistoryPoint> priceHistory;
  final List<MarketOrder> sellOrders;
  final List<MarketOrder> buyOrders;
  final List<RecentTransaction> recentTransactions;

  ShareMarketDataModel({
    required this.propertyId,
    required this.propertyName,
    required this.currentPrice,
    required this.highPrice24h,
    required this.lowPrice24h,
    required this.priceChange24h,
    required this.priceChangePercentage24h,
    required this.totalVolume24h,
    required this.priceHistory,
    required this.sellOrders,
    required this.buyOrders,
    required this.recentTransactions,
  });

  factory ShareMarketDataModel.fromJson(Map<String, dynamic> json) {
    return ShareMarketDataModel(
      propertyId: json['propertyId'] ?? '',
      propertyName: json['propertyName'] ?? '',
      currentPrice: (json['currentPrice'] ?? 0).toDouble(),
      highPrice24h: (json['highPrice24h'] ?? 0).toDouble(),
      lowPrice24h: (json['lowPrice24h'] ?? 0).toDouble(),
      priceChange24h: (json['priceChange24h'] ?? 0).toDouble(),
      priceChangePercentage24h: (json['priceChangePercentage24h'] ?? 0).toDouble(),
      totalVolume24h: json['totalVolume24h'] ?? 0,
      priceHistory: (json['priceHistory'] as List?)
          ?.map((e) => PriceHistoryPoint.fromJson(e))
          .toList() ?? [],
      sellOrders: (json['sellOrders'] as List?)
          ?.map((e) => MarketOrder.fromJson(e))
          .toList() ?? [],
      buyOrders: (json['buyOrders'] as List?)
          ?.map((e) => MarketOrder.fromJson(e))
          .toList() ?? [],
      recentTransactions: (json['recentTransactions'] as List?)
          ?.map((e) => RecentTransaction.fromJson(e))
          .toList() ?? [],
    );
  }
}

class PriceHistoryPoint {
  final DateTime timestamp;
  final double price;
  final int volume;

  PriceHistoryPoint({
    required this.timestamp,
    required this.price,
    required this.volume,
  });

  factory PriceHistoryPoint.fromJson(Map<String, dynamic> json) {
    return PriceHistoryPoint(
      timestamp: DateTime.parse(json['timestamp']),
      price: (json['price'] ?? 0).toDouble(),
      volume: json['volume'] ?? 0,
    );
  }
}

class MarketOrder {
  final double price;
  final int quantity;
  final int cumulativeQuantity;

  MarketOrder({
    required this.price,
    required this.quantity,
    required this.cumulativeQuantity,
  });

  factory MarketOrder.fromJson(Map<String, dynamic> json) {
    return MarketOrder(
      price: (json['price'] ?? 0).toDouble(),
      quantity: json['quantity'] ?? 0,
      cumulativeQuantity: json['cumulativeQuantity'] ?? 0,
    );
  }
}

class RecentTransaction {
  final DateTime timestamp;
  final double price;
  final int quantity;
  final String type; // 'buy' or 'sell'

  RecentTransaction({
    required this.timestamp,
    required this.price,
    required this.quantity,
    required this.type,
  });

  factory RecentTransaction.fromJson(Map<String, dynamic> json) {
    return RecentTransaction(
      timestamp: DateTime.parse(json['timestamp']),
      price: (json['price'] ?? 0).toDouble(),
      quantity: json['quantity'] ?? 0,
      type: json['type'] ?? 'buy',
    );
  }
}
