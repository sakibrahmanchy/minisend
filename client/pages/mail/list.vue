<template>
  <Table api-url="/mail" :generate-query="generateQuery" :bus="bus">
    <template #filters>
      <div class="row p-2">
        <div class="col-md-7">
          <input class="form-control border-primary" placeholder="Search by from, to & subject.." @input="debouncedSearch"/>
        </div>
        <div class="col-md-4">
          <select class="form-control" @change="debouncedStatusChange">
            <option value="-1">All</option>
            <option value="0">POSTED</option>
            <option value="1">SENT</option>
            <option value="2">FAILED</option>
          </select>
        </div>
        <div class="col-md-1">
          <fa icon="redo" @click="fetchData"></fa> Refresh
        </div>
      </div>
    </template>
    <template #no-data>
      <div class="text-xl-center p-5 text-success">
        <fa icon="envelope-open"  style="font-size: 200px"></fa>
        <h4 class="p-5 text-muted">No emails! Start creating by clicking on the + icon!</h4>
      </div>
    </template>
    <template #header>
      <th>Subject</th>
      <th>From</th>
      <th>To</th>
      <th>Date</th>
      <th>Status</th>
      <th>Retry</th>
    </template>
    <template #default="props">
      <td>
        <router-link :to="{ name: 'mail.details', params: { id: props.row.id } }" exact>
          {{ props.row.subject }}
        </router-link>
      </td>
      <td>
        {{ props.row.from }}
      </td>
      <td>
        <a @click="filterByRecipient(props.row.to)" href="javascript:void(0)">{{ props.row.to }}</a>
      </td>
      <td>
        {{ props.row.created_at }}
      </td>
      <td>
        <span :class="getStatusType(props.row.status).class" class="p-2 text-white">
          <a href="javascript:void(0)" class="text-white" data-toggle="tooltip" :title="props.row.failure_reason">
            {{ getStatusType(props.row.status).text }}
          </a>
        </span>
      </td>
      <td>
        <fa v-if="props.row.status === '0'" icon="redo" @click="retry(props.row.id)"></fa>
      </td>
    </template>
    <template #loader>
      <Loader />
    </template>
  </Table>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import Table from '../../components/global/Table'
import Loader from '../../components/global/Loader'
export default {
  name: 'mail-list',
  components: { Loader, Table },
  data: () => ({
    search: '',
    debounce: null,
    status: -1,
    bus: new Vue()
  }),
  methods: {
    retry (id) {
      axios.get(`/mail/${id}/resend`).then((res) => {
        this.bus.$emit('fetch-data')
      })
    },
    generateQuery (url) {
      let generatedUrl = url

      if (generatedUrl[generatedUrl.length - 1] !== '?') {
        generatedUrl = `${generatedUrl}?`
      }

      if (this.search && !generatedUrl.includes(this.search)) {
        generatedUrl = `${generatedUrl}&search=${this.search}`
      }

      if (Number(this.status) !== -1 && !generatedUrl.includes(this.status)) {
        generatedUrl = `${generatedUrl}&status=${this.status}`
      }

      const countOfQ = generatedUrl.split('?').length - 1

      if (countOfQ > 1) {
        let index = 0
        generatedUrl = generatedUrl.replace(/\?/g, item => (!index++ ? item : ''))
      }

      return generatedUrl
    },
    filterByRecipient (recipient) {
      this.bus.$emit('fetch-data', { url: `/mail?to=${recipient}` })
    },
    fetchData() {
      this.bus.$emit('fetch-data')
    },
    debouncedSearch (e) {
      clearTimeout(this.debounce)
      this.search = e.target.value
      this.debounce = setTimeout(() => {
        this.fetchData()
      }, 600)
    },
    debouncedStatusChange (e) {
      clearTimeout(this.debounce)
      this.status = e.target.value
      this.debounce = setTimeout(() => {
        this.fetchData()
      }, 200)
    },
    getStatusType (status) {
      switch (status) {
        case '1':
          return {
            class: 'bg-success',
            text: 'SENT'
          }
        case '2':
          return {
            class: 'bg-danger',
            text: 'FAILED'
          }
        default:
          return {
            class: 'bg-info',
            text: 'POSTED'
          }
      }
    }
  }
}
</script>

<style scoped>

</style>
