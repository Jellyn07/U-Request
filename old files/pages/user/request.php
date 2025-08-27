<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request</title>
    <link rel="stylesheet" href="/public/assets/css/output.css" />
    <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  </head>
  <body class="bg-background text-text">
    <?php include __DIR__ . '/../../components/header.php'; ?>
    <main class="">
      <h1 class="text-lg font-bold mb-1 mt-8 md:flex flex-1 justify-center">Welcome to U-Request!</h1>
      <p class="text-text mb-1 mt-1 md:flex flex-1 justify-center text-sm">Select a request...</p>
      <div class="flex">
        <article class="ml-20 w-1/2 m-5 rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
            <img id="logo-img" src="/public/assets/img/mechanic1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Repair Request
            </h3>
            <p class="mt-2 mb-5 line-clamp-3 text-sm/relaxed text-gray-500">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae dolores, possimus pariatur
              animi temporibus nesciunt praesentium dolore sed nulla ipsum eveniet corporis quidem, mollitia
              itaque minus soluta, voluptates neque explicabo tempora nisi culpa eius atque dignissimos.
              Molestias explicabo corporis voluptatem?
            </p>
          <a class="bg-secondary text-background p-2 rounded-md text-sm" >Request Now</a>
        </article>
        <article class="mr-20 w-1/2 m-5 rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
            <img id="logo-img" src="/public/assets/img/minicar1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Vehicle Request
            </h3>
            <p class="mt-2 mb-5 line-clamp-3 text-sm/relaxed text-gray-500">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae dolores, possimus pariatur
              animi temporibus nesciunt praesentium dolore sed nulla ipsum eveniet corporis quidem, mollitia
              itaque minus soluta, voluptates neque explicabo tempora nisi culpa eius atque dignissimos.
              Molestias explicabo corporis voluptatem?
            </p>
          <a class="bg-secondary text-background p-2 rounded-md text-sm" >Request Now</a>
        </article>
      </div>
    </main>
    <?php include __DIR__ . '/../../components/footer.php'; ?>
  </body>
</html>