<template>
	<NcAppContent>
		<div id="cerberus">
			<h1>Hello world!</h1>
		</div>
	</NcAppContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'

export default {
	name: 'App',
	components: {
		NcAppContent,
	},
}
</script>

<style scoped lang="scss">
#cerberus {
	display: flex;
	justify-content: center;
	margin: 16px;
}
</style>
