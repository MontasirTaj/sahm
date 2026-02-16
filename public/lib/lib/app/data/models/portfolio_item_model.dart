class PortfolioItemModel {
  final String id;
  final String propertyId;
  final String propertyName;
  final String propertyImage;
  final String propertyType;
  final int sharesOwned;
  final double purchasePrice;
  final double currentPrice;
  final double totalInvested;
  final double currentValue;
  final double profitLoss;
  final double profitPercentage;
  final DateTime investmentDate;
  final double totalDividends;
  final double? lastDividendAmount;
  final DateTime? lastDividendDate;

  PortfolioItemModel({
    required this.id,
    required this.propertyId,
    required this.propertyName,
    required this.propertyImage,
    required this.propertyType,
    required this.sharesOwned,
    required this.purchasePrice,
    required this.currentPrice,
    required this.totalInvested,
    required this.currentValue,
    required this.profitLoss,
    required this.profitPercentage,
    required this.investmentDate,
    required this.totalDividends,
    this.lastDividendAmount,
    this.lastDividendDate,
  });

  factory PortfolioItemModel.fromJson(Map<String, dynamic> json) {
    return PortfolioItemModel(
      id: json['id'] ?? '',
      propertyId: json['propertyId'] ?? '',
      propertyName: json['propertyName'] ?? '',
      propertyImage: json['propertyImage'] ?? '',
      propertyType: json['propertyType'] ?? '',
      sharesOwned: json['sharesOwned'] ?? 0,
      purchasePrice: (json['purchasePrice'] ?? 0).toDouble(),
      currentPrice: (json['currentPrice'] ?? 0).toDouble(),
      totalInvested: (json['totalInvested'] ?? 0).toDouble(),
      currentValue: (json['currentValue'] ?? 0).toDouble(),
      profitLoss: (json['profitLoss'] ?? 0).toDouble(),
      profitPercentage: (json['profitPercentage'] ?? 0).toDouble(),
      investmentDate: json['investmentDate'] != null
          ? DateTime.parse(json['investmentDate'])
          : DateTime.now(),
      totalDividends: (json['totalDividends'] ?? 0).toDouble(),
      lastDividendAmount: json['lastDividendAmount']?.toDouble(),
      lastDividendDate: json['lastDividendDate'] != null
          ? DateTime.parse(json['lastDividendDate'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'propertyId': propertyId,
      'propertyName': propertyName,
      'propertyImage': propertyImage,
      'propertyType': propertyType,
      'sharesOwned': sharesOwned,
      'purchasePrice': purchasePrice,
      'currentPrice': currentPrice,
      'totalInvested': totalInvested,
      'currentValue': currentValue,
      'profitLoss': profitLoss,
      'profitPercentage': profitPercentage,
      'investmentDate': investmentDate.toIso8601String(),
      'totalDividends': totalDividends,
      'lastDividendAmount': lastDividendAmount,
      'lastDividendDate': lastDividendDate?.toIso8601String(),
    };
  }

  bool get isProfitable => profitLoss > 0;
}
