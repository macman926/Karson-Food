<?php
   
?>
var qsEditM = document.querySelector('#eStudentCount');
var inputStudentCount = document.querySelector('#iStudentCount');
var updatedStudentCount = document.querySelector('#updatingCount');
qsEditM.addEventListener("click", function (){
    var jq_config={url:  '/?p=<?=($US->getHash())?>/order/<?=($r['id'])?>&view=edit'};
    //alert(inputStudentCount.value);
    if(inputStudentCount.value == ''){
        alert("Please enter value to be updated.");
    } else {
        jq_ajax_call_w_config(
            {
                page: 'orderSingleFieldUpdate',
                field:'STUDENTCOUNT',
                val: inputStudentCount.value,
            }
            ,jq_config
        );
    }   
    }
);
