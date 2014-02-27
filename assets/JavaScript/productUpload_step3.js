
/**
 *
 *  Add location on click - adds new select field for delivery location
 *
 *  Shipping summary mouse events - displays edit and delete buttons for 
 *  location vs price fields in Summary Table
 *
 */
$(function(){
  $('#add_location').on('click',function(){
    var datacount = $('#shiploc_count').val();
    $('#shiploc_count').val(+datacount+1);
    var selecttrnew = $('#shiploc_selectiontbl').find('select[name="shiploc1"]').closest('tr').clone();
    selecttrnew.find('select[name^="shiploc"]')[0].name = "shiploc"+ (+datacount+1);
    selecttrnew.find('input[name^="shipprice"]')[0].name = "shipprice"+ (+datacount+1);
    selecttrnew.append('<td><span class="delete_locrow button">Remove</td>');
    $('#shiploc_selectiontbl').find('tr:last').before('<tr>' + selecttrnew.html() + '</tr>');
  });

  $('#shiploc_selectiontbl').on('click', '.delete_locrow', function(){
    $(this).closest('tr').remove();
    var shipLocCount = $('#shiploc_count');
    var locCount = parseInt(shipLocCount.val());
    shipLocCount.val(locCount-1);
  });
});
/********** CLOSE DOCUMENT READY FUNCTION *********/


/**
*
* This function section contains behavioral functions in creating the summary field.
* 
* No data is sent to the server until the submit button is hit
*
* fdata - contains final data to be sent to the server
* displaygroup - temporary object to contain attributes per group(row) in the Shipping Summary Table.
*              - used to easily identify where to insert location vs price if attr(group) already exists in summary table
*
*/
$(function(){
  var fdata = {};
  var displaygroup = {};
  var spanerror = '<span class="error red samelocerror">Unable to select same location for same attribute</span>';
  var shiplocselectiontbl = $('#shiploc_selectiontbl');
  
  /**
  * Add Shipping Details to Summary list on-click event
  */
  $('#add_shipping_details').on('click', function(){
    var hasActive = hasLoc = hasPrice = false;
    var noDuplicate = true;
    var shipObj = { 'attr' : {},'loc' : {},'price' : {} };   
    var i = parseInt($('#summaryrowcount').val());

    //Get Product Attribute Options
    $('.product_combination.active').each(function(){
      shipObj.attr[$(this).val()] = $.trim($(this).text());
      hasActive = true;
    });

    //Get Shipping Location Select(s) and corresponding Price
    $('.shiploc').each(function(){
      var selopt = $(this).find('option:selected');
      var price = $(this).parent('td').next('td').children('input[name^="shipprice"]');
      
      if(selopt.val() !== 0 && price.val() !== 0 && price.val() !== '' ){
        shipObj.loc[selopt.val()] = selopt.text();
        shipObj.price[selopt.val()] = price.val();
        hasLoc = hasPrice = true;
      }
    });

    //Check for duplicate entry of attr vs location
    jQuery.each(shipObj.attr, function(attrk,attrv){
      jQuery.each(shipObj.loc, function(lock, locv){
        jQuery.each(fdata, function(groupkey,attrObj){
          if(attrk in attrObj){
            if(lock in attrObj[attrk]){
              noDuplicate = false;
            }
          }
        });  
      });
    });

    //If all fields are filled up and no duplicate entry
    if(hasActive && hasLoc && hasPrice && noDuplicate){
      var row = $('table#shipping_summary tr.cloningfield').clone();
      row.removeClass('cloningfield');
      row.find('td:first').html('');
      
      var summaryExists = addDispGroup = false;
      var groupkey = i;

      if(i !== 0 ){
        jQuery.each(displaygroup, function(k,shipObjTemp){
          if(objectCompare(shipObj.attr, shipObjTemp)){
              row = $('table#shipping_summary').find('tr[data-group="' + k + '"]');
              groupkey = k;
              summaryExists = true;
              return;
          }
        });
      }
      else{
        addDispGroup = true;
        $('#summaryrowcount').val(+i+1);
      }

      //Show table and submit button
      if($('#shipping_summary').hasClass('tablehide')){
        $('#shipping_summary').removeClass('tablehide');
        $('#btnShippingDetailsSubmit').removeClass('tablehide');
      }

      //Display location and price
      var nesttable = row.find('table.shiplocprice_summary > tbody');
      var nesttabletr = nesttable.find('tr.cloningfield').clone();
      nesttabletr.removeClass('cloningfield');
      jQuery.each(shipObj.loc, function(k,v){
        nesttabletr.children('td:first').html(v);
        nesttabletr.children('td:last').hide();
        var PriceField = nesttabletr.children('td:nth-child(2)');
        PriceField.attr('data-value', shipObj.price[k]);
        PriceField.html(shipObj.price[k]);
        nesttable.append('<tr data-idlocation='+k+' data-groupkey='+groupkey+'>'+nesttabletr.html()+'</tr>');
      });

      //Append new summary details as new row if summary does not exist
      if(!summaryExists){
        addDispGroup = true;
        $('#summaryrowcount').val(+i+1);
        jQuery.each(shipObj.attr, function(k,v){
          row.children('td:first').append(v + "<br />");
        }); 
        $('table#shipping_summary').append('<tr class="tr_shipping_summary" data-group="'+i+'">' + row.html() + '</tr>');
      }

      //Recondition fields and variables
      $('.shiploc').not('[name="shiploc1"]').each(function(){
        $(this).closest('tr').remove();
      });
      $('.product_combination').each(function(){
        $(this).removeClass('active');
      });
      shiplocselectiontbl.find('select[name="shiploc1"]').val(0);
      shiplocselectiontbl.find('input[name="shipprice1"]').val('');
      $('#shiploc_count').val(1);
      

      if(!(groupkey in fdata)){
        fdata[groupkey] = {};
      }

      //Push data to fdata object - to be sent to server
      jQuery.each(shipObj.attr, function(attrk, attrv){
        if(!(attrk in fdata[groupkey])){
          fdata[groupkey][attrk] = {};
        }
        jQuery.each(shipObj.loc, function(lock, locv){
          fdata[groupkey][attrk][lock] = shipObj.price[lock];
        });
      });

      if(addDispGroup)
        displaygroup[i] = shipObj.attr;

    }//close hasloc hasactive hasprice
  });//close on click of adding ship details to summary
  

  /**
  * Submit Handler function
  */
  $('#btnShippingDetailsSubmit').on('click', function(){
    if(getObjectSize(fdata) > 0){
	  var csrftoken = $('#shippingsummary_csrf').val();
      $.post(config.base_url+'productUpload/step3Submit', {fdata : fdata, es_csrf_token : csrftoken}, function(data){

      });
    }
  });

  /**
  * On change event for Location select field
  */
  $('#shiploc_selectiontbl').on('change', '.shiploc', function(){
    var currval = $(this).find('option:selected').val();
    var thistr = $(this).closest('tr');
    var hasDuplicate = false;

    $(this).parent('td').siblings('span.error.red').remove();

    $('.shiploc').not(this).each(function(){
      var otherval = $(this).find('option:selected').val();
      if(currval === otherval){
        hasDuplicate = true;
        return;
      }
    });
    $('.product_combination.active').each(function(){
      var attrk = $(this).val();
      
      jQuery.each(fdata, function(groupkey,attrObj){
        if(attrk in attrObj){
          if(currval in attrObj[attrk]){
            hasDuplicate = true;
            return;
          }
        }
      });
    });

    if(hasDuplicate){
      thistr.find('input[name^="shipprice"]').val('');
      thistr.append(spanerror);
      $(this).val(0);
    }

  });

  /**
  * On click event for Attribute Selection
  */ 
  $('.product_combination').on('click', function(){

    $('span.samelocerror').remove();

    if($(this).hasClass('active')){
      $(this).removeClass('active');
    }
    else{
      var attrk = $(this).val();
      $(this).addClass('active');

      jQuery.each(fdata, function(groupkey,attrObj){
        if(attrk in attrObj){
          $('.shiploc').each(function(){
            var lock = $(this).find('option:selected').val();
            var thistr = $(this).closest('tr');

            if(lock in attrObj[attrk]){
              thistr.find('input[name^="shipprice"]').val('');
              thistr.append(spanerror);
              $(this).val(0);
            }
          });
        }
      });
    }

  });

  /**
  * Shipping Summary button functions
  */ 
  $('#shipping_summary').on('click', '.delete_summaryrow', function(){
    var group = $(this).closest('tr').attr('data-group');
    $(this).closest('tr').remove();
    delete fdata[group];
    delete displaygroup[group];
    hideTable();
  })
  .on('click', '.edit_summaryrow', function(){
    $(this).hide();
    $(this).siblings('.edit_del').hide();
    $(this).siblings('.accept_cancel').show();

    $(this).parent('td').prev('td').find('tr').each(function(){
      var PriceField = $(this).find('td:nth-child(2)');
      var PriceValue = PriceField.text();
      PriceField.html('<input type="text" value="'+PriceValue+'">');
      PriceField.next('td').show();
    });
  })
  .on('click', '.accept_summaryrow', function(){
    var PriceLocRows = $(this).parent('td').prev('td').find('tr:not(".cloningfield")');
    var isFilled = true;

    PriceLocRows.each(function(){
      var inputPriceField = $(this).find('td:nth-child(2)').find('input');
      var newPrice = parseInt($.trim(inputPriceField.val()));
      if(isNaN(newPrice) || newPrice === 0){
        inputPriceField.effect('pulsate',{times:3},800);
        isFilled = false;
        return;
      }
    });

    if(isFilled){
      $(this).hide();
      $(this).siblings('.edit_del').show();
      $(this).siblings('.accept_cancel').hide();

      PriceLocRows.each(function(){
        var PriceField = $(this).find('td:nth-child(2)');
        var newPrice = parseInt(PriceField.find('input').val());
        var groupkey = $(this).attr('data-groupkey');
        var idlocation = $(this).attr('data-idlocation');
        PriceField.attr('data-value', newPrice);
        PriceField.html(newPrice);
        PriceField.next('td').hide();
        jQuery.each(fdata[groupkey], function(attrk, attrObj){
          attrObj[idlocation] = newPrice;
        });
      });
    }
  })
  .on('click', '.cancel_summaryrow', function(){
    var PriceLocRows = $(this).parent('td').prev('td').find('tr:not(".cloningfield")');
    $(this).hide();
    $(this).siblings('.edit_del').show();
    $(this).siblings('.accept_cancel').hide();

    PriceLocRows.each(function(){
      var PriceField = $(this).find('td:nth-child(2)');
      var origValue = PriceField.attr('data-value');
      PriceField.html(origValue);
      PriceField.next('td').hide();
    });
  })
  // Delete button per Location VS Price
  .on('click', '.delete_priceloc', function(){
    var parentTr = $(this).closest('tr');
    var groupkey = parentTr.attr('data-groupkey');
    var idlocation = parentTr.attr('data-idlocation');
    jQuery.each(fdata[groupkey], function(attrk, attrObj){
      delete attrObj[idlocation];
    });
    
    if(parentTr.siblings('tr:not(".cloningfield")').length === 0){
      parentTr.parent().closest('tr').remove();
      delete fdata[groupkey];
      delete displaygroup[groupkey];
      hideTable();
      $('.edit_del').show();
      $('.accept_cancel').hide();
    }
    parentTr.remove();
  });

  /**
  * Function to hide Summary Table and Submit Button.
  * Checks if DisplayGroup Object is empty - meaning nothing displayed - before execution
  */
  function hideTable(){
    if(getObjectSize(displaygroup) === 0){
      $('#shipping_summary').addClass('tablehide');
      $('#btnShippingDetailsSubmit').addClass('tablehide');
    }
  }

});

/********** CLOSE DOCUMENT READY FUNCTION WITH DATA variables *********/


/*
* Compares if two objects are identical
*/
function objectCompare(o1, o2){
  for(var p in o1){
    if(o1[p] !== o2[p]){
      return false;
    }
  }
  for(var p in o2){
    if(o1[p] !== o2[p]){
      return false;
    }
  }
  return true;
}

/*
* Get object size
*/
function getObjectSize(obj) {
    var len = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) len++;
    }
    return len;
};