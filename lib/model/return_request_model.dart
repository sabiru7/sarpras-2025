import 'dart:io';

import 'item_model.dart';

class ReturnRequestModel {
  final int id;
  final int loanId;
  final String returnDate;
  final String status;
  final int quantity;
  final File? photo;
  final List<ReturnDetailModel> details;

  ReturnRequestModel({
    required this.id,
    required this.loanId,
    required this.returnDate,
    required this.status,
    required this.quantity,
    this.photo,
    required this.details,
  });

  factory ReturnRequestModel.fromJson(Map<String, dynamic> json) {
    var detailsFromJson = json['details'] as List<dynamic>? ?? [];
    List<ReturnDetailModel> detailsList =
        detailsFromJson.map((e) => ReturnDetailModel.fromJson(e)).toList();

    return ReturnRequestModel(
      id: json['id'],
      loanId: json['loan_id'],
      returnDate: json['return_date'],
      status: json['status'],
      quantity: json['quantity'],
      photo: json['photo'],
      details: detailsList,
    );
  }
}

class ReturnDetailModel {
  final int id;
  final int returnRequestId;
  final int itemId;
  final ItemModel item;

  ReturnDetailModel({
    required this.id,
    required this.returnRequestId,
    required this.itemId,
    required this.item,
  });

  factory ReturnDetailModel.fromJson(Map<String, dynamic> json) {
    return ReturnDetailModel(
      id: json['id'],
      returnRequestId: json['return_request_id'],
      itemId: json['item_id'],
      item: ItemModel.fromJson(json['item']),
    );
  }
}
