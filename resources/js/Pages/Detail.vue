<template>
    <app :app="app">
        <l-page-header :title="$t('page.detail')">
            <l-btn v-if="backRoute" type="submit" variant="outline-secondary" :href="backRoute">
                {{ $t('btn.goback') }}
            </l-btn>
        </l-page-header>
        <l-panel>
            <l-field v-for="(label, column) of columns" horizontal :key="column" :label="label">
                <div v-if="column === 'media_id'" v-html="data[column]" />
                <l-plain-text v-else :value="data[column]" />
            </l-field>
        </l-panel>

        <l-panel v-for="(relation, index) in relations" :key="index" :title="relation.title">
            <template #header>
                <div>
                    <l-btn size="sm" variant="secondary" :href="relation.routes.create">
                        {{ $t('btn.create') }}
                    </l-btn>
                    <l-btn v-if="relation.routes.attach" size="sm" variant="outline-secondary" class="ms-2" :href="relation.routes.attach">
                        {{ $t('btn.attach') }}
                    </l-btn>
                </div>
            </template>
            <l-data-table :data="relation.data" :headers="relation.columns" :sortable="false" />
        </l-panel>
    </app>
</template>

<script>
import App from '@/Layouts/App'

export default {
    components: {
        App
    },
    props: {
        app: {
            type: Object,
            required: true
        },
        data: {
            type: Object,
            required: true
        },
        columns: {
            type: Object,
            required: true
        },
        relations: {
            type: Object
        },
        backRoute: {
            type: String,
            default: ''
        }
    }
}
</script>
