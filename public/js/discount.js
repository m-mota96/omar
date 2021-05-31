$('#btnDiscount').click(()=> {
    $('#btnDiscount').prop('disabled', true);
    $.ajax({
        url: $('#URL').val()+'discount',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            quantity: $('#quantity').val()
        },
        success: (res)=> {
            $('#btnDiscount').prop('disabled', false);
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});

function calculate(action) {
    var quantity = parseInt($('#quantity').val());
    if (action == 'minus') {
        if (quantity > 1) {
            quantity = quantity - 1;
        }
    } else if (action == 'more') {
        quantity = quantity + 1;
    }
    $('#quantity').val(quantity);
}