class TransactionModel {
  final String id;
  final String userId;
  final String propertyId;
  final String propertyName;
  final String type; // purchase, sale, dividend, deposit, withdrawal
  final int? shares;
  final double amount;
  final double? pricePerShare;
  final double? fee;
  final String status; // pending, completed, cancelled, failed
  final DateTime createdAt;
  final DateTime? completedAt;
  final String? notes;

  TransactionModel({
    required this.id,
    required this.userId,
    required this.propertyId,
    required this.propertyName,
    required this.type,
    this.shares,
    required this.amount,
    this.pricePerShare,
    this.fee,
    required this.status,
    required this.createdAt,
    this.completedAt,
    this.notes,
  });

  factory TransactionModel.fromJson(Map<String, dynamic> json) {
    return TransactionModel(
      id: json['id'] ?? '',
      userId: json['userId'] ?? '',
      propertyId: json['propertyId'] ?? '',
      propertyName: json['propertyName'] ?? '',
      type: json['type'] ?? '',
      shares: json['shares'],
      amount: (json['amount'] ?? 0).toDouble(),
      pricePerShare: json['pricePerShare']?.toDouble(),
      fee: json['fee']?.toDouble(),
      status: json['status'] ?? 'pending',
      createdAt: json['createdAt'] != null
          ? DateTime.parse(json['createdAt'])
          : DateTime.now(),
      completedAt: json['completedAt'] != null
          ? DateTime.parse(json['completedAt'])
          : null,
      notes: json['notes'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'userId': userId,
      'propertyId': propertyId,
      'propertyName': propertyName,
      'type': type,
      'shares': shares,
      'amount': amount,
      'pricePerShare': pricePerShare,
      'fee': fee,
      'status': status,
      'createdAt': createdAt.toIso8601String(),
      'completedAt': completedAt?.toIso8601String(),
      'notes': notes,
    };
  }

  bool get isCompleted => status == 'completed';
  bool get isPending => status == 'pending';
  bool get isCancelled => status == 'cancelled';
  bool get isFailed => status == 'failed';
}
