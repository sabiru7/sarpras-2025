import 'item_model.dart';
import 'user_model.dart';

class LoanModel {
  final int id;
  final int userId;
  final int itemId;
  final int quantity;
  final String borrowedAt;
  final String returnedAt;
  final String status;
  final ItemModel item;
  final UserModel user;

  LoanModel({
    required this.id,
    required this.userId,
    required this.itemId,
    required this.quantity,
    required this.borrowedAt,
    required this.returnedAt,
    required this.status,
    required this.item,
    required this.user,
  });

  factory LoanModel.fromJson(Map<String, dynamic> json) {
    return LoanModel(
      id: json['id'],
      userId: json['user_id'],
      itemId: json['item_id'],
      quantity: json['quantity'],
      borrowedAt: json['borrowed_at'],
      returnedAt: json['returned_at'],
      status: json['status'],
      item: ItemModel.fromJson(json['item']),
      user: UserModel.fromJson(json['user']),
    );
  }
}
