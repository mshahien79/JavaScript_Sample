@extends('layouts.home')

@section('content')
<script type="text/javascript">

function checkavailablestock() {
    var current_stock_count_male = parseInt(document.getElementById("stock_av_count_male").value);
    var current_stock_count_female = parseInt(document.getElementById("stock_av_count_female").value);
    var needed_count = parseInt(document.getElementById("sales_quantity").value);
    var kind = document.getElementById("kind").value;
    if ((needed_count > current_stock_count_male)&&(kind == 'male'))
     {
        alert("Insuffecient stock! , كمية غير متاحة");
        document.getElementById("sales_quantity").value = current_stock_count_male;
    }
    else if ((needed_count > current_stock_count_female)&&(kind == 'female'))
    {
       alert("Insuffecient stock! , كمية غير متاحة");
       document.getElementById("sales_quantity").value = current_stock_count_female;     
    }
    calculate_sales();

}

function calculate_sales() 
{
  var unit_wgt = parseFloat(document.getElementById("unit_wgt").value);
  var selling_cost = parseFloat(document.getElementById("selling_cost").value);
  var quantity = parseFloat(document.getElementById("sales_quantity").value);
  if (isNaN(unit_wgt)) 
   {
    alert('Invalid Weight!');
    unit_wgt = 0;
    document.getElementById("unit_wgt").value = unit_wgt;
   } 
   if (isNaN(selling_cost)) 
   {
    alert('Invalid Cost!');
    selling_cost = 0;
    document.getElementById("selling_cost").value = selling_cost;
   }
   if (isNaN(quantity)) 
   {
    alert('Invalid quantity!');
    quantity = 0;
    document.getElementById("quantity").value = quantity;
   }
   var sub_total = unit_wgt * selling_cost * quantity;
   document.getElementById("sub_total").value = sub_total;
   document.getElementById("sales_total").value = sub_total;  
   document.getElementById("sales_count").value = quantity;
   var opening_due = document.getElementById("opening_due").value;
   var opening_balance = document.getElementById("opening_balance").value;
   var discount = document.getElementById("discount_amount").value;
   var tax = document.getElementById("tax_amount").value;
   var grand_total = (parseFloat(sub_total) + parseFloat(opening_due) - parseFloat(opening_balance) - parseFloat(discount) + parseFloat(tax) );
   document.getElementById("grand_total").value = grand_total;
   var payment = document.getElementById("payment").value;
   if( (grand_total - payment) > 0 ){
          var closing = grand_total - payment;
        }
        else{
          var closing = payment - grand_total;
        }

   document.getElementById("closing_balance").value = closing;
   
   document.getElementById("closing_due").value =  grand_total - payment;

}


function sales_discount()
{
  var discount_percent = document.getElementById("discount_percent").value;
  var sub_total = document.getElementById("sub_total").value;
  var discount = parseFloat( (sub_total * discount_percent)/100 ).toFixed(2);
  document.getElementById("discount_amount").value = discount;
  calculate_sales();
}
 
function sales_tax()
{
  var tax_percent = document.getElementById("tax_percent").value;
  var sub_total = document.getElementById("sub_total").value;
  var tax = parseFloat( (sub_total * tax_percent)/100 || 0 ).toFixed(2);
  document.getElementById("tax_amount").value = tax;
  calculate_sales();
}


function clearQuantity()
{
  document.getElementById("sales_quantity").value = 0;
}
</script>

<section class="content-header">
  <h1>
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">اضافة مبيعات</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">

<div class="row">
  <div class="col-sm-12">
    <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"> {{ $BatchName }}:  اضافة مبيعات لباتش </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <form class="form-horizontal" role="form" method="POST" action="{{ url('/sales/store') }}">

              {{ csrf_field() }}

              <div class="box-body">

                <div class="box box-default">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>اسم العميل</label>
                          <input type="text" class="form-control search_customer_name" placeholder="Type here ..." name="customer_name">
                          <span class="help-block search_customer_name_empty" style="display: none;">No Results Found ...</span>

                          <span class="help-block search_purchase_category_name_empty" style="display: none;">No Results Found ...</span>
                          <input type="hidden" class="search_customer_id" name="customer_id">
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>العنوان</label><br>
                          <input type="text" class="form-control search_customer_address" name="customer_address">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>تليفون</label><br>
                          <input type="text" class="form-control search_customer_contact1" name="customer_contact1">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Opening Balance</label><br>
                          <input type="text" name="opening_balance" class="form-control opening_balance" readonly="" id="opening_balance">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Opening Due</label><br>
                          <input type="text" name="opening_due" class="form-control opening_due" readonly="" id="opening_due">
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="box box-default">

                    <div class="box-body">
                    @foreach ($stocks as $stock)
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>نوع المنتج</th>
                            <th> ذكر / رأس</th>
                            <th> أنثى / رأس</th>
                            <th>النوع</th>
                            <th>الوزن عند البيع</th>
                            <th>سعر البيع / كيلو</th>
                            <th>عدد </th>
                            <th>الاجمالي</th>
                          </tr>
                        </thead>
                        <tbody class="sales_container">
                          <tr>
                            <td width="100px">
                              <input type="text" class="form-control" placeholder="Type here ..." name="category_name[]" autocomplete="off" value="{{$stock->category_name}}" readonly="True">
                              <span class="help-block search_purchase_category_name_empty glyphicon" style="display: none;"> No Results Found </span>
                              <input type="hidden" class="search_category_id" name="category_id[]">
                            </td>
                            <td width="100px">
                             <input type="hidden" class="form-control " name="stock_id" value="{{$stock->stock_id}}" readonly="True">
                             <input type="text" class="form-control " name="stock_av_count_male" id="stock_av_count_male" value="{{$stock->male_stock_quantity}}" readonly="True">

                              <div class="form-group">

                                <input type="hidden" class="form-control" placeholder="Type here ..." name="BatchName" readonly="True" value="{{$BatchName}}" >
                                <input type="hidden" class="search_batch_id" name="batch_id">
                              </div>
                            </td>
                            <td width="100px">
                            <input type="text" class="form-control " name="stock_av_count_female" id="stock_av_count_female" value="{{$stock->female_stock_quantity}}" readonly="True">
                            </td>
                            <td width="25px">
                              <select name="kind" id="kind" onclick="clearQuantity()">
                                <option value="male" selected="selected">ذكر</option>
                                <option value="female" >أنثى</option>
                              </select>
                            </td>
                            <td width="100px">
                              <input type="text" class="form-control" name="unit_wgt" id="unit_wgt" onkeyup="calculate_sales()" value="0">
                            </td>
                            <td width="100px">
                              <input type="text" class="form-control " name="price_per_kg" id="selling_cost" onkeyup="calculate_sales()" value="0">
                            </td>

                            <td width="100px">
                              <input type="hidden" class="form-control" name="opening_stock[]">

                              <input type="hidden" class="closing_stock" name="closing_stock[]">

                              <input type="text" class="form-control" name="sales_quantity[]" id="sales_quantity" min="10" onkeyup="checkavailablestock()"  value="0">

                              <small class="help-block max_stock" style="display: none;">Insufficient Stock</small>
                            </td>

                            <td width="100px">
                              <input type="text" class="form-control" name="sub_total[]"  readonly="" id="sub_total">
                            </td>

 
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="3">

                            </td>
                            <td></td>
                          </tr>
                        </tfoot>
                      </table>

                      <div class="row">
                      <div class="col-md-offset-4 col-md-4">
                      <div class="form-group">

                            <label>اجمالي المبيعات</label><br>
                            <input type="text" class="form-control" readonly="" name="sales_total" id="sales_total">


                      </div>
                      </div>
                        <div class="col-md-4">
                      <div class="form-group">
                            <label>عدد الوحدات المبيعة</label><br>
                            <input type="text" class="form-control" readonly="" name="sales_count" id="sales_count">
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                          <div class="form-group">
                            <label>خصم ( % )</label><br>
                            <input type="number" class="form-control" name="discount_percent" step="0.01" min="0" max="100" value="0" id="discount_percent" onchange="sales_discount()" >
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>خصم ( Amount )</label><br>
                            <input type="text" class="form-control" name="discount_amount" step="0.01" min="0" value="0" id="discount_amount">
                          </div>
                        </div>
                      </div>

                      <div class="row">

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>تفاصيل ضرائب</label><br>
                            <input type="text" class="form-control" name="tax_description">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>ضريبة ( % )</label><br>
                            <input type="number" class="form-control" name="tax_percent"  step="0.01" min="0" max="100" value="0" id="tax_percent" onchange="sales_tax()">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>ضريبة ( Amount )</label><br>
                            <input type="text" class="form-control" name="tax_amount"   step="0.01" min="0" value="0" id="tax_amount">
                          </div>
                        </div>
                      </div>

                    </div>
                </div>

                <div class="box box-default">
                  <div class="box-body">
                    <div class="row">

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>تفاصيل المبيعات</label><br>
                          <textarea class="form-control" style="height: 35px;" name="sales_description"></textarea>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>الاجمالي الكلي</label><br>
                          <input type="text" class="form-control" name="grand_total" readonly="" id="grand_total">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>المدفوع</label><br>
                          <input type="text" class="form-control" name="payment" id="payment" onkeyup="calculate_sales()">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Closing Balance</label><br>
                          <input type="text" class="form-control" name="closing_balance" id="closing_balance" readonly="">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Closing Due</label><br>
                          <input type="text" class="form-control" name="closing_due" id="closing_due" readonly="">
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Mode</label>
                          <select class="form-control" name="mode">
                            <option value="1">Cash</option>
                            <option value="2">Cheque</option>
                            <option value="3">Card</option>
                          </select>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div>

            @endforeach
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="reset" class="btn btn-danger pull-left">اعادة ملء</button>
                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> اضافة</button>
              </div>
      </form>
    </div>
    <!-- /.box-body -->
    </div>
  </div>
</div>
</section>
<!-- /.content -->
@endsection