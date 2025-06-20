class ItemModel {
  final int id;
  final String name;
  final int quantity;
  final String? photo;
  final String? description;
  final Category? category;

  ItemModel({
    required this.id,
    required this.name,
    required this.quantity,
    this.photo,
    this.description,
    required this.category,
  });

  factory ItemModel.fromJson(Map<String, dynamic> json) {
    return ItemModel(
      id: json['id'],
      name: json['name'],
      quantity: json['quantity'],
      photo: json['photo'],
      description: json['description'],
      category:
          json['category'] != null ? Category.fromJson(json['category']) : null,
    );
  }
}

class Category {
  final int id;
  final String name;

  Category({required this.id, required this.name});

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(id: json['id'], name: json['name']);
  }
}
