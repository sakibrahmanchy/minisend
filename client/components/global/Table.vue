<template>
  <div>
    <slot name="filters" />
    <div v-if="!rows.length" class="text-center">
      <slot name="no-data" />
    </div>
    <div v-else ref="scrollComponent" class="wrapper" @scroll="handleScroll">
      <table class="table table-striped ">
        <thead>
          <tr>
            <slot name="header" />
          </tr>
        </thead>
        <tbody>
          <tr v-for="row of rows" :key="row.id">
            <slot :row="row" />
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="loading">
      <slot name="loader"></slot>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Table',
  props: {
    apiUrl: {
      default: ''
    },
    generateQuery: {
      default: () => {}
    },
    bus: {
      default: null
    }
  },
  data: () => ({
    rows: [],
    columns: [],
    next: null,
    loading: false
  }),
  created () {
    if (this.bus) {
      this.bus.$on('fetch-data', (args) => {
        if (args && args.url) {
          this.fetchData(args.url)
        } else {
          this.fetchData(this.apiUrl)
        }
      })
    }
  },
  mounted () {
    this.next = this.apiUrl
    this.fetchData()
    if (window) {
      window.addEventListener('scroll', this.handleScroll)
    }
  },
  destroyed () {
    if (window) {
      window.removeEventListener('scroll', this.handleScroll)
    }
  },
  methods: {
    capitalize (str) {
      return `${str[0].toUpperCase()}${str.slice(1, str.length)} `
    },
    handleScroll (e) {
      const element = this.$refs.scrollComponent

      if (element.getBoundingClientRect().bottom < window.innerHeight) {
        this.fetchData()
      }
    },
    fetchData (url = null) {
      if ((url || this.next) && !this.loading) {
        this.loading = true
        axios.get(this.generateQuery(url || this.next)).then((res) => {
          this.loading = false
          if (res.status === 200) {
            const {
              data: {
                data = null,
                next_page_url: nextPageUrl = null,
                prev_page_url: previousPageUrl = null
              } = {}
            } = res.data
            if (data) {
              if (url) {
                this.rows = data
              } else {
                this.rows = [...this.rows, ...data]
              }
              if (data.length) {
                this.columns = Object.keys(this.rows[0]).map(key => key)
              }
              this.next = nextPageUrl
              this.prev = previousPageUrl
            }
          }
        })
      }
    }
  },
  handleScroll () {
    const scrollComponent = this.$refs.scrollComponent
    const element = scrollComponent.value
    if (element.getBoundingClientRect().bottom < window.innerHeight) {
      this.fetchData()
    }
  }
}
</script>

<style lang="scss" scoped>
.wrapper{
  min-height: 500px;
  height: 500px;
  overflow: scroll;
}
</style>
