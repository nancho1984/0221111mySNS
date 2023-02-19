<HTML>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
 <script type="text/javascript" src="https://unpkg.com/vue-simple-suggest"></script>
 <link rel="stylesheet" href="https://unpkg.com/vue-simple-suggest/dist/styles.css">
<div id="app">
  <vue-simple-suggest
      v-model="selected"
      :list="suggestionList"
      :filter-by-query="true">
  </vue-simple-suggest>
</div>
<script>
  const app = new Vue({
    el: '#app',
    data: {
      selected: null,
      suggestionList: ['Canada', 'China', 'Cameroon', "Italy", "Iraq", "Iceland"]
    }
  })
</script>
</HTML>