/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var states = [],
    selectedState = null,
    modal = null,
    numPaq = 0,
    infinito = false,
    interval = null,
    xhr = 0,
    charts = {},
    map = {};
$(function(){
    $('.graphic-content').find('canvas').each(function(){
        var name = $(this).attr('id').replace('grafico-','');
        if($.type(dataGraf[name]) === 'undefined'){
            $(this).remove();
        }else{
            var legenda = $('#legend-grafico-'+name),
                el = document.getElementById("grafico-"+name),
                elj = $("#grafico-"+name);

                el.width = elj.parent().width();
                el.height = elj.parent().width()*0.5;
    //            legenda.height(el.height);
                legenda.css({'max-height': el.height});

                var ctx = el.getContext("2d"),
                    chart = null;
                $('#select-graph-'+name).on('change', function(){
                    var parent = $(this).closest('.graphic-content'),
                        name = parent.find('canvas').attr('id').replace('grafico-',''), 
                        type = $(this).find('input[name="option-graph"]:checked').val();
                    if(charts[name]){
                        charts[name].destroy();
                    }
                    switch(type){
                        case 'pie':
                            chart = new Chart(ctx).Pie(getDataGraph(dataGraf[name], type),optsPie);
                            break;
                        case 'line':
                            chart = new Chart(ctx).Line(getDataGraph(dataGraf[name], type),optsLine);
                            break;
                        case 'bar':
                            chart = new Chart(ctx).Bar(getDataGraph(dataGraf[name], type),optsBar);
                            break;
                    }
                    charts[name] = chart;
                }).trigger('change');
                $('#reload-grafico-'+name).on('click', function(e){
                    var parent = $(this).closest('.graphic-content'),
                        name = parent.find('canvas').attr('id').replace('grafico-','');
                    $('#select-graph-'+name).trigger('change');
                });
                legend(document.getElementById("legend-grafico-"+name),dataGraf[name]);
                charts[name] = chart;
                elj.on('mousemove', function(e){
                    var parent = $(this).closest('.graphic-content'),
                        name = parent.find('canvas').attr('id').replace('grafico-','');
                    if(charts[name]){
                        var active = getDatasetEvent(e, charts[name]);
                        if(active.length){
                            for(var j = 0; j < active.length; j++){
                                var d = active[j],
                                    text = unescape(d.title?d.title:d.label),
                                    itemLegend = legenda.children(":contains('"+text+"')"),
                                    color = 'transparent';
                                if(active.length > 1){
                                    text = d.dataGrafsetLabel;
                                }
                                if(itemLegend.text() === text){
                                    color = '#eee';
                                }
                                legenda.children().css({'background': 'transparent'});
                                itemLegend.css({'background': color});
                            }
            //                legenda.children().each(function(){
            //                    for(var j = 0; j < active.length; j++){
            //                        var d = active[j],
            //                            text = d.title?d.title:d.label,
            //                            color = 'transparent';
            //                        if($(this).text() === text){
            //                            color = '#eee';
            //                        }
            //                        $(this).css({'background': color});
            //                    }
            //                });
                        }else{
                            legenda.children().css({'background': 'transparent'});
                        }
                    }
                });
        }
    });
    // modal
     var modalOpts = {
         'backdrop' :   'static',
         'keyboard' :   false,
         'show'     :   false
     };
     modal = $('#modal').modal(modalOpts);
     modal.on('click', '.modal-closed', function(){
         $('.stop-medicion').first().trigger('click');
         modal.modal('hide');
         modal.find('#modal-body').html('');
         modal.find('#modal-title').html('Midiendo');
     });
     modal.on('click', '.stop-medicion', function(){
//         xhr.abort();
         numPaq = 0;
         infinito = false;
         clearInterval(interval);
     });
    // open-modal
     $(document).on('click','.open-modal',function(e){
        e.preventDefault();
        e.stopPropagation();
        var url = $(this).attr('href');
        openModalAjax(url); 
     });
    // ajax-form
    $('.ajax-form').on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        var url = $(this).attr('action'),
            dataA = $(this).serializeArray();
        ;
        var data = [];
        for(var d = 0; d < dataA.length; d++){
            data[d] = dataA[d];
            var val = $('[name="'+data[d].name+'"]').attr('data-val');
            if(val){
                data[d].value = val;
            }
            if(data[d].name === "qos_medicionesbundle_medicioninstitucion[numPaquetes]"){
                numPaq = parseInt(data[d].value);
                if(!numPaq || numPaq <= 0){
                    infinito = true;
                }
            }
        }
        sendAjax(url, data);
    });
    // Typeahead
    activeTypeahead();
});

function activeTypeahead($typeahead){
    if($.type($typeahead) === 'undefined'){
        $typeahead = '.typeahead';
    }
    $($typeahead).each(function(){
        var este = $(this);
        
        $(this)
            .on('focusout', function(){
                if(este.val() === '' || !map[este.val()]){
                    este.removeAttr('data-val');
                }
            })
            .typeahead({
                minLength: 1,
                source: function (query, process) {

                    var text = este.attr('placeholder'),
                        id = '',
                        url = este.closest('form').attr('action') + 'Lista-de-';
                    switch(text){
                        case 'proveedor':
                        case 'Proveedor':
                            id = $('#qos_medicionesbundle_medicioninstitucion_institucion').attr('data-val');
                            text = 'Proveedor';
                            break;
                        case 'institucion':
                        case 'institución':
                        case 'Institucion':
                        case 'Institución':
                            id = $('#qos_medicionesbundle_medicioninstitucion_proveedor').attr('data-val');
                            text = 'Institucion';
                            break;
                    }
                    url += text + 'es/' + (id?id:'');
                    states = [];
                    map = {};
                    $.get(url, function(data){
                        $.each(data, function (i, state) {
                            map[state.stateName] = state;
                            states.push(state.stateName);
                        });
                        process(states)
                    },'json');
                },
                updater: function (item) {
                    if(map[item]){
                        selectedState = map[item].stateCode;
                        este.attr('data-val',selectedState)
                    }else{
                        este.attr('data-val','');
                        item = '';
                    }
                    return item;
                },
                matcher: function (item) {
                    var abrevia = map[item].abreviacion, q = this.query.trim().toLowerCase();
                    if (item.toLowerCase().indexOf(q) !== -1 || abrevia.toLowerCase().indexOf(q) !== -1) {
                        return true;
                    }
                },
                sorter: function (items) {
                    return items.sort();
                },
                highlighter: function (item) {
                    var regex = new RegExp( '(' + this.query + ')', 'gi' );
                    return item.replace( regex, "<strong>$1</strong>" );
                }
            });
    });
}

function openModalAjax(url, data, method){
    if($.type(method) === 'undefined'){
        method = 'GET';
    }
    var $datos = {
        url: url,
        type: method,
        cache: false,
        beforeSend: function(jqXHR, settings){
        }
    };
    if(data){
        $datos.data = data;
    }
    xhr = $.ajax($datos).then(function(data, textStatus, jqXHR){
        modal.find('#modal-body').children().remove();
        if(data.body){
            var body = modal.find('#modal-body');
            body.append(data.body);
            activeTypeahead(body.find('.typeahead'));
            if(body.find('form')){
                body.find('form').on('submit', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var url = $(this).attr('action'),
                        dataA = $(this).serializeArray();
                    ;
                    var data = [];
                    for(var d = 0; d < dataA.length; d++){
                        data[d] = dataA[d];
                        var val = $('[name="'+data[d].name+'"]').attr('data-val');
                        if(val){
                            data[d].value = val;
                        }
                    }
                    openModalAjax(url, data, $(this).attr('method'));
                });
            }
        }
        if(data.title){
            modal.find('#modal-title').text(data.title);
        }
        modal.find('.stop-medicion').hide();
        modal.modal('show');
    }, function(jqXHR, textStatus, errorThrown){
        
    }).always(function(){
    });
}

function humanize(size) {
    var units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];
    var ord = Math.floor(Math.log(size) / Math.log(1024));
    ord = Math.min(Math.max(0, ord), units.length - 1);
    var s = Math.round((size / Math.pow(1024, ord)) * 100) / 100;
    return s + ' ' + units[ord];
}

function sendAjax(url, data){
    xhr = $.ajax({
        url: url,
        data: data,
        type: 'PATCH',
        cache: false,
        beforeSend: function(jqXHR, settings){
        }
    }).then(function(data, textStatus, jqXHR){
        if(!modal.find('#modal-body').find('ol').length){
            modal.find('#modal-body').append('<ol class="row"></ol>')
        }
        if(data.urlFile){
            sendFileAjax(url, $(data.form).serializeArray(), data.urlFile);
        }
        modal.find('#modal-title').text('Midiendo')
        interval = setInterval(function(){
            modal.find('#modal-title').text(modal.find('#modal-title').text()+'.')
            if((modal.find('#modal-title').text().match(/\./g)||[]).length > 3){
                modal.find('#modal-title').text('Midiendo');
            }
        }, 1000);
        modal.find('.stop-medicion').show();
        modal.modal('show');
    }, function(jqXHR, textStatus, errorThrown){
        
    }).always(function(){
        
    });
}

function sendFileAjax(url, data, urlFile){
    xhr = $.ajax({
        url: urlFile,//+"?timestamp=" + new Date().getTime(),
        type: 'POST',
        cache: false,
        beforeSend: function(jqXHR, settings){
        }
    }).then(function(data1, textStatus, jqXHR){
        numPaq--;
        console.log(data1)

        modal.find('#modal-body>ol').append('<li><div class="col-xs-4">'+data1.timeTotal+' segundos</div><div class="col-xs-4">'+humanize(data1.sizeUpload)+' subidos</div><div class="col-xs-4">'+humanize(data1.lengthDownload)+' descargados</div></li>')
        
        if(numPaq > 0 || infinito){
            setTimeout(function(){
                sendAjax(url, data);
            },1000);
        }
    }, function(jqXHR, textStatus, errorThrown){
        
    }).always(function(){
        
    });
}