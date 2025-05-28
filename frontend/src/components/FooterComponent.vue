<template>
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p class="small">A brief description of your company or project can go here.</p>
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <address class="small">
                        <p>123 Street Name, City</p>
                        <p>Email: example@email.com</p>
                        <p>Phone: (123) 456-7890</p>
                    </address>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center small">
                <p class="mb-0">&copy; {{ new Date().getFullYear() }} Your Company Name. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'FooterComponent'
}
</script>

<style scoped>
footer a:hover {
    text-decoration: underline;
}
</style>