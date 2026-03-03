namespace ECommerceApi.DTOs;

public class PlaceOrderRequest
{
    public int ProductId { get; set; }
    public int Quantity { get; set; }
    public string PaymentMethod { get; set; } = "credit_card";
}

public class PlaceOrderCartRequest
{
    public List<PlaceOrderItemRequest> Items { get; set; } = new();
    public string PaymentMethod { get; set; } = "credit_card";
}

public class PlaceOrderItemRequest
{
    public int ProductId { get; set; }
    public int Quantity { get; set; }
}

public class OrderItemDto
{
    public int SellId { get; set; }
    public string ProductName { get; set; } = string.Empty;
    public int ProductId { get; set; }
    public int Quantity { get; set; }
    public decimal TotalPrice { get; set; }
}

public class OrderDto
{
    public int Id { get; set; }
    public DateTime OrderDate { get; set; }
    public string PaymentMethod { get; set; } = string.Empty;
    public List<OrderItemDto> Items { get; set; } = new();
    public decimal TotalAmount { get; set; }
}

/// <summary>Order with customer info (for admin view only).</summary>
public class OrderWithCustomerDto : OrderDto
{
    public int UserId { get; set; }
    public string CustomerEmail { get; set; } = string.Empty;
}

public class SellDetailDto
{
    public int Id { get; set; }
    public int OrderId { get; set; }
    public DateTime SellDate { get; set; }
    public string CustomerEmail { get; set; } = string.Empty;
    public string ProductName { get; set; } = string.Empty;
    public int ProductId { get; set; }
    public int Quantity { get; set; }
    public decimal UnitPrice { get; set; }
    public decimal TotalPrice { get; set; }
    public string PaymentMethod { get; set; } = string.Empty;
}
