<div id="PopupContainer" class="modelContainer" style="min-height: 200px; min-width: 300px">
    <div class="modal-header contentsBackground">
        <button type="button" class="close " data-dismiss="modal"
                aria-hidden="true">×
        </button>
        <h3 id="massEditHeader">{$TITLE}</h3>
    </div>
    <div class="slimScrollDiv" style="padding: 10px;line-height: 2.2em;">
        <span>{$QUESTION}</span>
    </div>
    <div class="modal-footer">
        <div class="pull-right cancelLinkContainer" style="margin-top:0;">
            <input type="hidden" name="record_id" value="{$RECORD_ID}">
            <button type="button" id="case1" class="btn btn-success">
                <strong>{$CASE1}</strong>
            </button>
            <button type="button" id="case2" class="btn btn-danger" data-dismiss="modal">
                <strong>{$CASE2}</strong>
            </button>
        </div>
    </div>
</div>
