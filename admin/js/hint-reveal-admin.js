class RevealRow{
	constructor(id, columns){
		this.id = id,
		this.columns = columns
	}
}
class RevealColumn{
	constructor(id, field){
		this.id = id,
		this.field = field
	}
}

const hreveal = new Vue({
	el: "#reveal",
	data: {
		title: "",
		revealRows: [],
		isDisabled: false
	},
	methods: {
		get_reveal_data: function(){
			let vthis = this;
			let id = jQuery("#revealID").val();
			if(id !== 'new'){
				jQuery.ajax({
					type: "get",
					url: hintreveal.ajaxurl,
					data: {
						action: "get_reveal_data",
						nonce: hintreveal.nonce,
						id: id
					},
					beforeSend: () =>{
						vthis.isDisabled = true;
					},
					dataType: "json",
					success: function (response) {
						hreveal.isDisabled = false;
						hreveal.title = response.title;
						hreveal.revealRows = response.data;
					}
				});
			}
		},
		add_reveal_row: function (){
			this.revealRows.push(new RevealRow(Date.now(), [new RevealColumn(Date.now(), "")]))
		},
		add_reveal_column: function(id){
			let row = this.revealRows.find(el => parseInt(id) === parseInt(el.id));
			row.columns.push(new RevealColumn(Date.now(), ""));
		},
		remove_reveal: function(rowId, colId){
			let row = this.revealRows.find(el => parseInt(rowId) === parseInt(el.id));
			row.columns = row.columns.filter(col => parseInt(colId) !== parseInt(col.id));
		},
		remove_row: function(rowID){
			this.revealRows = this.revealRows.filter(el => el.id !== rowID);
		},
		allAreEqual: function(array) {
			const result = array.every(element => {
			  if (element === array[0]) {
				return true;
			  }
			});
		  
			return result;
		},
		save_reveals: function(){
			let id = jQuery("#revealID").val();
			jQuery.ajax({
				type: "post",
				url: hintreveal.ajaxurl,
				data: {
					action: "save_hint_reveals",
					nonce: hintreveal.nonce,
					title: hreveal.title,
					data: hreveal.revealRows,
					id: id
				},
				beforeSend: () =>{
					hreveal.isDisabled = true;
				},
				dataType: "json",
				success: function (response) {
					hreveal.isDisabled = false;
					if(response.redirect){
						location.href = response.redirect;
					}
				}
			});
		}
	},
	created: function(){
		if(jQuery("#revealID").val()){
			this.get_reveal_data();
		}
	},
	computed:{
		valid_columns(){
			let columnsSizes = [];
			this.revealRows.forEach(row => {
				columnsSizes.push(row.columns.length);
			});
			return this.allAreEqual(columnsSizes);
		} 
	}
})
