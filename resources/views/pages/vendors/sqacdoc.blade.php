@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'SQAC Doc')

@section('content')


		<!-- begin panel -->
		<div class="panel panel-inverse ">
			<!-- begin panel-heading -->
			<div class="panel-heading">
				<h4 class="panel-title">SQAC Document </h4>
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
						data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				</div>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div id="popup"></div>
                <div id="grid-sqacdoc" style="height: 640px; width:100%;"></div>
			</div>
			<!-- end panel-body -->
		</div>
		<!-- end panel -->

@endsection

@push('scripts')
<script>

var store = new DevExpress.data.CustomStore({
    key: "id",
    load: function() {
        return sendRequest(apiurl + "/sqacdoc");
    },
    insert: function(values) {
        return sendRequest(apiurl + "/sqacdoc", "POST", values);
    },
    update: function(key, values) {
        return sendRequest(apiurl + "/sqacdoc/"+key, "PUT", values);
    },
    remove: function(key) {
        return sendRequest(apiurl + "/sqacdoc/"+key, "DELETE");
    },
});

function moveEditColumnToLeft(dataGrid) {
    dataGrid.columnOption("command:edit", { 
        visibleIndex: -1,
        width: 80 
    });
}

var id = {},
    popup = null,
    popupOptions = {
        width: 500,
        height: 450,
        contentTemplate: function() {
            console.log('new : '+id);
            var scrollView = $("<div />").append(
                $("<p>On Air: <span>" + title1 + "</span></p>"),
                $("<div>").attr("id", "formupload").dxFileUploader({
                    uploadMode: "useButtons",
                    name: "file_upload",
                    uploadUrl: "/api/upload-berkas/"+id+"/onair",
                    accept: "image/*",
                    // accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    onUploaded: function (e) {						
                        dataGrid.refresh();
                        // popup.hide();
                    }
                }),
                $("<p>LV: <span>" + title2 + "</span></p>"),
                $("<div>").attr("id", "formupload").dxFileUploader({
                    uploadMode: "useButtons",
                    name: "file_upload",
                    uploadUrl: "/api/upload-berkas/"+id+"/lv",
                    accept: "image/*",
                    // accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    onUploaded: function (e) {						
                        dataGrid.refresh();
                        // popup.hide();
                    }
                }),
                $("<p>KPI LV 4G: <span>" + title3 + "</span></p>"),
                $("<div>").attr("id", "formupload").dxFileUploader({
                    uploadMode: "useButtons",
                    name: "file_upload",
                    uploadUrl: "/api/upload-berkas/"+id+"/kpi4g",
                    accept: "image/*",
                    // accept: "image/*,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    onUploaded: function (e) {						
                        dataGrid.refresh();
                        // popup.hide();
                    }
                })
            );
            
            scrollView.dxScrollView({
                width: '100%',
                height: '100%'
            });

            return scrollView;

        },
        showTitle: true,
        title: "Upload Form",
        dragEnabled: false,
        closeOnOutsideClick: false
};

var showUpload = function(data) {
    // $('#popup').html('');
    id = data;
    // if(file1 !== null) {
        
    //     title1 = file1;
    // } else if(file2 !== null) {
        
    //     title2 = file2;
    // } else if(file3 !== null) {
        
    //     title3 = file3;
    // } else {
    //     title = "belum ada lampiran";
    // }
    $.post(apiurl + "/getsqacattachment",{id:id},function(items){
        console.log(items.data);
        
        // (items.data[0].typefile == 'onair') ? title1 = items.data[0].namefile : title1 = "File belum ada";
        // (items.data[1].typefile == 'lv') ? title2 = items.data[1].namefile : title2 = "File belum ada";
        // (items.data[2].typefile == 'kpi4g') ? title3 = items.data[2].namefile : title3 = "File belum ada";
        // (file2 !== null) ? title2 = file2 : title2 = "File belum ada";
        // (file3 !== null) ? title3 = file3 : title3 = "File belum ada";

        if(typeof items.data[0] == 'undefined') {
            title1 = "File belum ada"
        } else {
            (items.data[0].typefile == 'onair') ? title1 = items.data[0].namefile : title1 = "File belum ada";
        }

        if(typeof items.data[1] == 'undefined') {
            title2 = "File belum ada"
        } else {
            (items.data[1].typefile == 'lv') ? title2 = items.data[1].namefile : title2 = "File belum ada";
        }

        if(typeof items.data[2] == 'undefined') {
            title3 = "File belum ada"
        } else {
            (items.data[2].typefile == 'kpi4g') ? title3 = items.data[2].namefile : title3 = "File belum ada";
        }

        console.log(title3)
        
        
        
        if(popup) {
            popup.option("contentTemplate", popupOptions.contentTemplate.bind(this));
        } else {
            popup = $("#popup").dxPopup(popupOptions).dxPopup("instance");
        }
        
        popup.show();
    })
};

var dataGrid = $("#grid-sqacdoc").dxDataGrid({    
    dataSource: store,
    allowColumnReordering: true,
    allowColumnResizing: true,
    columnsAutoWidth: true,
    // columnMinWidth: 150,
    columnHidingEnabled: false,
    wordWrapEnabled: true,
    showBorders: true,
    filterRow: { visible: true },
    filterPanel: { visible: true },
    headerFilter: { visible: true },
    selection: {
        mode: "multiple"
    },
    editing: {
        useIcons:true,
        mode: "row",
        allowAdding: true,
        // allowUpdating: true,
        allowUpdating(e) {
            if(e.row.data.requeststatus == 0) {
                return true;
            }
        },
        // allowDeleting: true,
        allowDeleting(e) {
            if(e.row.data.requeststatus == 0) {
                return true;
            }
        },
    },
    scrolling: {
        mode: "virtual"
    },
    columns: [
        {
            caption: '#',
            formItem: { 
                visible: false
            },
            width: 40,
            cellTemplate: function(container, options) {
                container.text(options.rowIndex +1);
            }
        },
        {
            caption: 'Tambah/Edit Berkas',
            formItem: {visible:false},
            fixed: false,
            visible: (role=="vendor")?true:false,
            editorOptions: {
                disabled: true
            },
            cellTemplate: function(container, options) {
                $('<button class="btn btn-success btn-xs">Upload</button>').addClass('dx-button').on('dxclick', function(evt) {
                    evt.stopPropagation();
                    if(options.data.requeststatus == 0) {
                        showUpload(
                            options.data.id,
                            // options.data.file1,
                            // options.data.file2,
                            // options.data.file3,
                        );
                    } else {
                        error('Sedang dalam pengajuan! Akses tidak di izinkan.')
                    }
                    }).appendTo(container);
            }
        },
        {
            caption: 'Action',
            formItem: {visible:false},
            fixed: false,
            visible: (role=="vendor")?true:false,
            editorOptions: {
                disabled: true
            },
            cellTemplate: function(container, options) {
            if(options.data.requeststatus == 0) {
                $('<button class="btn btn-danger btn-xs">Submit</button>').addClass('dx-button').on('dxclick', function(evt) {
                    evt.stopPropagation();
                        console.log('submit')
                }).appendTo(container);
            }
            
            }
        },
        {dataField:'requeststatus',encodeHtml: false ,width: 200,editorOptions: {
                disabled: true
            },
        customizeText: function (e) {
            var rDesc = ["<span class='mb-2 mr-2 badge badge-pill badge-secondary'>Saved as Draft</span>","<span class='mb-2 mr-2 badge badge-pill badge-primary'>Waiting Approval</span>","<span class='mb-2 mr-2 badge badge-pill badge-warning'>Require Rework</span>","<span class='mb-2 mr-2 badge badge-pill badge-success'>Approved</span>","<span class='mb-2 mr-2 badge badge-pill badge-danger'>Rejected</span>",""];
            return rDesc[e.value];
        }},
        { 
            dataField: "site_no",
            validationRules: [{type: "required"}]

        },
        { 
            dataField: "site_name",
            validationRules: [{type: "required"}]
        },
        { 
            dataField: "scoope",
            validationRules: [{type: "required"}]
        },
        { 
            dataField: "vendor",
            validationRules: [{type: "required"}]
        },
        { 
            dataField: "document",
            validationRules: [{type: "required"}]
        },
        { 
            dataField: "submitted_date",
            dataType:"date", format:"dd-MM-yyyy",displayFormat: "dd-MM-yyyy",
            validationRules: [{type: "required"}]
        },
        { 
            dataField: "aging",
            validationRules: [{type: "required"}]
        },
        // { 
        //     caption: "On Air Certificate",
        //     dataField: "file1",
        //     visible: false,
        //     formItem: {visible:false},
        //     editorOptions: {
        //         disabled: true
        //     },
        // },
        // { 
        //     caption: "LV Certificate",
        //     dataField: "file2",
        //     visible: false,
        //     formItem: {visible:false},
        //     editorOptions: {
        //         disabled: true
        //     },
        // },
        // { 
        //     caption: "KPI LV 4G",
        //     dataField: "file3",
        //     visible: false,
        //     formItem: {visible:false},
        //     editorOptions: {
        //         disabled: true
        //     },
        // },
        
    
    ],
    masterDetail: {
        enabled: true,
        template: masterDetailTemplate,

    },
    export: {
        enabled: true,
        fileName: "Data Master",
        excelFilterEnabled: true,
        allowExportSelectedData: true
    },
    onInitNewRow: function(e) {  
        // e.data.bulan = new Date().getMonth()+1;
        // e.data.tahun = new Date().getFullYear();
    } ,
    onContentReady: function(e){
        moveEditColumnToLeft(e.component);
    },
    onEditorPreparing: function(e) {

    },
    onToolbarPreparing: function(e) {
        dataGrid = e.component;

        e.toolbarOptions.items.unshift({						
            location: "after",
            widget: "dxButton",
            options: {
                hint: "Refresh Data",
                icon: "refresh",
                onClick: function() {
                    dataGrid.refresh();
                }
            }
        })
    },
}).dxDataGrid("instance");

function masterDetailTemplate(_, masterDetailOptions) {
  return $('<div>').dxTabPanel({
    items: [{
      title: 'Attachment',
      template: attachmentTab(masterDetailOptions.data),
    }, {
      title: 'Approver list',
      template: approverTab(masterDetailOptions.data),
    }],
  });
}

function attachmentTab(masterDetailData) {
    return function () {
        return $('<div>').dxDataGrid({
            columnAutoWidth: true,
            showBorders: true,
            rowAlternationEnabled: true,
            dataSource: new DevExpress.data.DataSource({
                store: new DevExpress.data.CustomStore({
                    key: 'sqac_id',
                    load: function() {
                        return sendRequest(apiurl + "/getsqacattachment","POST",{id:masterDetailData.id});
                    }
                }),
            }),
            columns: [
                { 
                    dataField: "namefile",
                    caption: "nama file",
                    name: "name1",
                },
                { 
                    dataField: "typefile",
                    caption: "type file",
                },
                { 
                    caption: "download",
                    dataField: "namefile",
                    name: "name2",
                    width: 150,
                    formItem: {visible:false},
                    editorOptions: {
                        disabled: true
                    },
                    cellTemplate: function(container, options) {
                        if(options.data.namefile !== null) {
                            $('<a href="/upload/'+options.data.namefile+'" target="_blank"><i class="fa fa-download"></i></a>').addClass('dx-link').appendTo(container);
                        }
                    }
                },
                { 
                    dataField: "status",
                    caption: "status",
                    encodeHtml: false,
                    customizeText: function (e) {
                        var rDesc = [
                            "<span class='mb-2 mr-2 badge badge-pill badge-primary'>Waiting Approval</span>",
                            "<span class='mb-2 mr-2 badge badge-pill badge-success'>Approved</span>",
                            "<span class='mb-2 mr-2 badge badge-pill badge-danger'>Rejected</span>",""
                        ];
                        return rDesc[e.value];
                    }
                }, 
                                
            ],
        });
    };
}

function approverTab(masterDetailData) {
    return function () {
        return $('<div>').dxDataGrid({
            columnAutoWidth: true,
            showBorders: true,
            rowAlternationEnabled: true,
            dataSource: new DevExpress.data.DataSource({
                store: new DevExpress.data.CustomStore({
                    key: 'sqac_id',
                    load: function() {
                        return sendRequest(apiurl + "/getsqacapproverlist","POST",{id:masterDetailData.id});
                    }
                }),
            }),
            columns: [
                { 
                    dataField: "approver_id",
                    caption: "nama",
                },
                { 
                    dataField: "approverstatus",
                    caption: "approver status",
                    encodeHtml: false,
                    customizeText: function (e) {
                        var rDesc = [
                            "<span class='mb-2 mr-2 badge badge-pill badge-primary'>Waiting Approval</span>",
                            "<span class='mb-2 mr-2 badge badge-pill badge-success'>Approved</span>",
                            "<span class='mb-2 mr-2 badge badge-pill badge-danger'>Rejected</span>",""
                        ];
                        return rDesc[e.value];
                    }
                }, 
                                
            ],
        });
    };
}

</script>

@endpush
