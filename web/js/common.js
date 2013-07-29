function jumpTo(pageNo,LastPage,e)
{
    if(e.keyCode == 13)
    {
        var jumpPage = parseInt(pageNo);
        if(!isNaN(jumpPage))
        {
            if(jumpPage <= LastPage && jumpPage > 0)
            {
                jQuery('#page').val(jumpPage);
                submitForm();
                return false;
            }
        }
    }
}

// Function to set previous page for global paging
function setPreviousPage()
{
    jQuery('#page').val((parseInt(jQuery('#page').val()) - 1));
    submitForm();
}

// Function to jump on page for global paging
function setCurrentPage(pageNo)
{
    var jumpPage = parseInt(pageNo);

    if(!isNaN(jumpPage))
    {
        jQuery('#page').val(jumpPage);
        submitForm();
        return false;
    }
}

// Function to set next page for global paging
function setNextPage()
{

    jQuery('#page').val((parseInt(jQuery('#page').val()) + 1));
    submitForm();
}

function submitForm(ssAct, id)
{
    ssUrl = ''//jQuery('#listing_form').attr('action');
    ssUrlParams = '';

    ssUrlParams += (jQuery('#page') && jQuery('#page').val() != '') ? 'page='+jQuery('#page').val() : '';

    ssUrlParams += (jQuery('#searchvalue') && jQuery('#searchvalue').val() != '') ? '&searchvalue='+jQuery('#searchvalue').val()+'&searchby='+(jQuery('#searchby').val() != undefined ? jQuery('#searchby').val() : '') : '';

    ssUrlParams += (jQuery('#sortby') && jQuery('#sortby').val() != '') ? '&sortby='+jQuery('#sortby').val()+'&sortmode='+jQuery('#sortmode').val() : '';

    var actions = ['delete', 'up', 'down'];

    if(contains(actions, ssAct))
    {
      ssUrlParams += '&mode='+ssAct+'&id='+id;
    }

    if(ssAct && ssAct != '')
    {
        ssUrlParams += (jQuery('#selection') && jQuery('#selection').val() != '') ? '&adminact='+ssAct+'&selection='+jQuery('#selection').val() : '';
    }


            jQuery.ajax({
                    type:'POST',
                    data:ssUrlParams,
                    dataType:'html',
                    cache: false,
                    success:function(data){
                        newData = jQuery.parseJSON(data);
                        jQuery('#number_counting').html(newData.number);
                        jQuery('#contentlisting').html(newData.content);
//                         jQuery('#listing_paging').html(newData.paging);
                    },
                    beforeSend:function(XMLHttpRequest){
                    },

                    complete:function(XMLHttpRequest){
                        },
                    url:ssUrl});

}

function contains(a, obj) {
    var i = a.length;
    while (i--) {
       if (a[i] === obj) {
           return true;
       }
    }
    return false;
}

function showAll()
{
    jQuery('#selectstatus').val('All');
    jQuery('#searchvalue').val('');
    jQuery('#searchby').val($("#searchby option:first").val());
    submitForm();
}

function setSorting(ssFieldName, ssOrder)
{
    jQuery('#sortby').val(ssFieldName);
    jQuery('#sortmode').val(ssOrder);
    submitForm()
}

function checkBoxCount()
{
    var checkBoxValue = jQuery('#selection').val().trim();
    return (checkBoxValue != '') ? checkBoxValue : false;
}


function uncheck(oCheckbox)
{
    var inactivateIds = document.getElementById('selection');

    var anCheck= eval('document.getElementsByName("' + oCheckbox.name + '")');
    var anInactiveIds = new Array();

    smStrId = oCheckbox.id.split("_");
    var snId = smStrId[(smStrId.length - 1)]; //Fetch the ID
    ssInactiveIds = inactivateIds.value;

    if(ssInactiveIds != '') anInactiveIds = ssInactiveIds.split(","); //Convert String to Array
        var bFlag = true;
    if(oCheckbox.checked)
    {
        anInactiveIds.push(snId);
        for(snX = 0; snX < anCheck.length; snX++ )
            if(!anCheck[snX].checked)
                bFlag = false;
    }
    else
    {
        bFlag = false;
        anInactiveIds  = removeItems(anInactiveIds, snId ); // Remove Item from the Array
    }

    document.getElementById('checkall').checked = (bFlag) ? true : false;

    inactivateIds.value = anInactiveIds.join(",") // Convert Array to String and assign to the hidden variable
}


function checkAll(oCheckbox, ssCheckbox, ssValueField)
{
    var anCommonId = eval('document.getElementsByName("id[]")');
    if(oCheckbox.checked)
    {
        var anId = new Array();
        for( snX = 0; snX < anCommonId.length; snX++ )
        {
            anCommonId[snX].checked = true;
            anId.push(anCommonId[snX].value);
        }
        if(anId && anId != '')
        {
            jQuery('#selection').val(anId);
        }
    }
    else
    {
        if(anCommonId.length > 0)
        {
            //document.getElementById('selection').value = '';
            jQuery('#selection').val('');
            for( snX = 0; snX < anCommonId.length; snX++ )
                anCommonId[snX].checked = false;
        }
    }
}

function removeItems(array, item)
{
    var i = 0;
    while (i < array.length) {
        if (array[i] == item) {
            array.splice(i, 1);
        } else {
        i++;
        }
    }
    return array;
}