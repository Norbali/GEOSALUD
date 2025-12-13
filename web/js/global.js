$(document).ready(function() {

    $(document).on("keyup","#filtro",function(){
        
        let data = $(this).val();
        let url = $(this).data("url");


        $.ajax({
            url: url,
            type: "GET",
            data: {
                buscar: data
            },
            success: function(data){
                $("tbody").html(data);
            }
        })

    });

    $(document).on("change","#filtro",function(){
        
        let data = $(this).val();
        let url = $(this).data("url");


        $.ajax({
            url: url,
            type: "GET",
            data: {
                buscar: data
            },
            success: function(data){
                $("tbody").html(data);
            }
        })

    });


});