export default{
    copyTheText(text){
        if(isNaN(text)){
            return alert('text not define')
        }
        $('#text-data-for-copy').show()
        $('#text-data-for-copy').val(text)
        this.$refs.textforcopy.focus();
        document.execCommand('copy');
        $('#text-data-for-copy').hide()
    }
}
