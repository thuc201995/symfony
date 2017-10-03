lang=$(".language").attr('id');
$(document).ready(function() {
    if($('#tabl_project').length > 0){
    var table = $('#tabl_project').DataTable( {
        'responsive': true,
         "ajax": {
            "url": "projectsAjax",
            "dataSrc": function(json){//format data from server here
                 for (var i=0,l=json.length; i<l; i++) {
                    val = json[i];
                    if(val.status==0){
                        (lang=="vn")?val.status='Chưa hoàn thành':val.status='Unfinished';
                        
                    }
                    else  (lang=="vn")?val.status='Hoàn thành':val.status='Finish';
                    if(lang=="vn"){
                        txt_edit="Sửa";
                        txt_delete="Xoá";
                    }
                    if(lang=="en"){
                        txt_edit="Edit";
                        txt_delete="Delete";
                    }
                     val.edit= "<a href=\"/edit/"+lang+"/"+val.id+"\" class=\"edit btn btn-primary waves-effect waves-light\"><i class=\"fa fa-edit\"></i><span> "+txt_edit+"</span>"
                    val.select= '  <input type="checkbox" name="id_select[]" value="'+val.id+'">'
                }
                return json;
            }
        },
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', 'project_' + aData.id); 
                },


        "columns":setColumns(1),
        "order": [[1, 'desc']],
        "language": setlangDatatable(),


    } );
   
        // Add event listener for opening and closing details
        $('#tabl_project tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        } );
    }

    if($("#success").text()){

        swal({
              text: $("#success").text(),
              icon: "success",
              button: "Close",
            });
    }
} );



function setColumns(x){
    if(x==1){
    return columns= [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "id" },
            { "data": "name" },
            { "data": "status" },
            { "data": "created_at" },
            { "data": "updated_at" },
            { "data": "edit" },
            { "data": "select" },

        ]
    }
}


//////rowdata



function format ( rowData ) {
    var div = $('<div/>')
        .addClass( 'loading' )
        .text( 'Loading...' );
    var url= "projectAjax/"+rowData.id;
    $.ajax( {
        url: url,
        data: '',
        dataType: 'json',
        success: function ( d ) {
            if(lang=="vn"){
                txt_performer="Người thực hiện";
                txt_content="Nội dung";
                txt_start="Ngày bắt đầu";
                txt_end="Ngày kết thúc"
            }
           if(lang=="en"){
                txt_performer="Performer";
                txt_content="Content"
                txt_start="Start date";
                txt_end="End date"
            }
            div
            .html('<table cellpadding="5" id="cell_'+d.id+'"cellspacing="0" border="0" style="padding-left:50px;" class="cellTable">'+
                '<tr>'+
                    '<td>'+txt_performer+'</td>'+
                    '<td><i>'+d[0].performer+'</i></td>'+
                '</tr>'+
                '<tr>'+
                    '<td>'+txt_start+'</td>'+
                    '<td><i>'+d[0].start+'</i></td>'+
                '</tr>'+
                '<tr>'+
                    '<td>'+txt_end+'</td>'+
                    '<td><i>'+d[0].end+'</i></td>'+
                '</tr>'+ 
                 '<tr>'+
                    '<td>'+txt_content+'</td>'+
                    '<td><i>'+d[0].content.replace(/(?:\\[rn]|[\r\n])/g,"<br>")+'</i></td>'+
                '</tr>'+ 
               
            '</table>'  
    )
                .removeClass( 'loading' );
        }
    } );
 
    return div;
}


function setlangDatatable(){

    setvn={
        "sProcessing":   "Đang xử lý...",
        "sLengthMenu":   "Xem _MENU_ mục",
        "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
        "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
        "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
        "sInfoFiltered": "(được lọc từ _MAX_ mục)",
        "sInfoPostFix":  "",
        "sSearch":       "Tìm:",
        "sUrl":          "",
        "oPaginate": {
            "sFirst":    "Đầu",
            "sPrevious": "Trước",
            "sNext":     "Tiếp",
            "sLast":     "Cuối"
        }
    };
    seten={
            "sEmptyTable":     "No data available in table",
            "sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty":      "Showing 0 to 0 of 0 entries",
            "sInfoFiltered":   "(filtered from _MAX_ total entries)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Show _MENU_ entries",
            "sLoadingRecords": "Loading...",
            "sProcessing":     "Processing...",
            "sSearch":         "Search:",
            "sZeroRecords":    "No matching records found",
            "oPaginate": {
                "sFirst":    "First",
                "sLast":     "Last",
                "sNext":     "Next",
                "sPrevious": "Previous"
            },
            "oAria": {
                "sSortAscending":  ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            }
        };
   switch(lang) {
    case "vn":
        return setvn;
         
        break;
    case "en":
        return seten;
          
        break;
    default:
        return seten;
           
           
    }
}


$(document).on("click","#delete",function(){
    id_select=$('input[name="id_select[]"]:checked').val();
    if(id_select==undefined){
        switch (lang) {
            case "en":
                swal({
              title: "Sorry!",
              text: "You haven't selected any items to delete!",
              icon: "warning",
              button: "Close",
            });
                break;
            case "vn":
                swal({
                  title: "Xin lỗi!",
                  text: "Bạn chưa chọn bất kỳ mục nào để xóa!",
                  icon: "warning",
                  button: "Thoát",
            });
           
                break;
        }
      
    }

    else $("#formDelete").submit();
})
