<div id="reveal">
    <div class="reveal_field">
        <div class="reveal_lbl">
            <label for="title">Title</label>
        </div>
        <div class="reveal_input">
            <input type="text" class="reveal_title" id="title" v-model="title">
            <input type="hidden" id="revealID" value="<?php echo ((isset($_GET['id'])) ? $_GET['id']: 'new') ?>">
        </div>
    </div>
    <div class="reveal_field">
        <div class="reveal_lbl">
            <label for="hints">Hints</label>
        </div>
        
        <div class="reveal_input">
            <div class="hints_editor">
                <div v-for="(row, index) in revealRows" :key="index" class="reveal_row">
                    <div class="reveal_columns">
                        <div v-for="(col, ind) in row.columns" :key="ind" class="reveal_column">
                            <input type="text" class="widefat" v-model="col.field">
                            <span v-if="row.columns.length > 1" @click="remove_reveal(row.id, col.id)" class="remove_col">+</span>
                        </div>
                    </div>
                    <button @click="add_reveal_column(row.id)" class="button-secondary">Add column</button>
                    <button @click="remove_row(row.id)" class="button-danger">Remove</button>
                </div>
                <button @click="add_reveal_row()" class="button-secondary">Add row</button>
            </div>
        </div>
    </div>

    <div v-if="isDisabled" class="hintLoader">
        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
            <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
            s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
            c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
            <path fill="#135e96" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
            C22.32,8.481,24.301,9.057,26.013,10.047z">
            <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.9s" repeatCount="indefinite"></animateTransform>
            </path>
        </svg>
    </div>

    <button @click="save_reveals()" :disabled="!valid_columns" class="button-primary" id="save_reveal">Save changes</button>
</div>