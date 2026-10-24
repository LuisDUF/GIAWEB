
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GIA</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: @json(env('VITE_GIA_PRIMARY', '#222831')),
                    secondary: @json(env('VITE_GIA_SECONDARY', '#393E46')),
                    blue: @json(env('VITE_GIA_BLUE', '#00ADB5')),
                    red: @json(env('VITE_GIA_RED', '#B5001B')),
                    green: @json(env('VITE_GIA_GREEN', '#00B576')),
                },
            },
        },
    };
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Honk:MORF@3&family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Iosevka+Charon+Mono:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Jersey+10&family=Poetsen+One&family=Walter+Turncoat&display=swap" rel="stylesheet">

<style>
    .poetsen-one-regular {
        font-family: "Poetsen One", sans-serif;
        font-weight: 400;
        font-style: normal;
    }
    .tilt-element {
        transform-style: preserve-3d;
        will-change: transform, box-shadow;
    }
</style>

</head>
<body class="bg-primary overflow-hidden">
        <div class="absolute inset-0 md:ml-12 ml-20 mt-[-4rem] bg-primary bg-[url(/images/patron.png)] bg-repeat-round h-screen w-100 opacity-30"></div>

        <div id="interactive-area" class="relative w-full h-screen">

            <div class="absolute z-9 w-full flex justify-center items-center md:mt-[24rem] mt-[22rem]">
                <!-- Div de "Crea una cuenta" con clase específica para el translateY -->
                <div id="create-account-card" class="tilt-element hover:cursor-pointer bg-blue rounded-3xl p-16 pb-3 px-10 h-50 md:min-w-[25rem] max-w-[25rem] flex flex-col gap-4 shadow-md justify-center items-center md:mt-[8rem] mt-[5rem]">
                    <h1 class="poetsen-one-regular text-primary font-bold text-center select-none">
                        Crea una cuenta
                    </h1>
                </div>
            </div>

            <div class="relative z-10 w-full justify-center grid gap-4 items-center md:mt-[8rem] mt-[5rem]">
                <div class="poetsen-one-regular text-white md:text-9xl text-5xl text-center select-none pointer-events-none">
                    GIA
                </div>

                <!-- Div del Formulario (sin translateY) -->
                <div id="form-card" class="tilt-element bg-secondary rounded-3xl p-16 px-10 w-full h-50 min-w-full md:min-w-[30rem] max-w-[30rem] flex flex-col gap-4 shadow-xl">
                    <form action="" class="flex flex-col gap-4">
                        <label class="select-none poetsen-one-regular text-white text-3xl" for="email">Correo</label>
                        <input class="rounded shadow-[inset_0_4px_6px_rgba(0,0,0,0.6)] bg-primary text-white placeholder:text-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue min-w-full p-2" type="email" id="email">
                        <label class="select-none poetsen-one-regular text-white text-3xl" for="password">Contraseña</label>
                        <input class="rounded shadow-[inset_0_4px_6px_rgba(0,0,0,0.6)] bg-primary text-white placeholder:text-gray-400 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue min-w-full p-2" type="password" id="password">
                        <div class="w-full items-center flex justify-center gap-4 mt-10">
                            <button class="bg-green p-4 text-white font-bold py-2 px-4 rounded shadow-md shadow-black hover:shadow-lg hover:shadow-black hover:transition duration-300 hover:translate-y-[-3px]" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check preview-icon"><path d="M20 6 9 17l-5-5"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

</body>
</html>

<script>
    const area = document.getElementById('interactive-area');
    const createAccountCard = document.getElementById('create-account-card');
    const formCard = document.getElementById('form-card');
    const tiltElements = document.querySelectorAll('.tilt-element');

    area.addEventListener('mousemove', (e) => {
        const rect = area.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = -((y - centerY) / centerY) * 4;
        const rotateY = ((x - centerX) / centerX) * 4;

        const shadowX = (x - centerX) / 15;
        const shadowY = (y - centerY) / 15;

        // Aplicar sombra a ambos
        tiltElements.forEach(el => {
            el.style.boxShadow = `${shadowX}px ${shadowY}px 30px rgba(0, 173, 181, 0.35), 0 15px 25px rgba(0, 0, 0, 0.4)`;
        });

        // Solo "Crea una cuenta" lleva el translateY(4px)
        createAccountCard.style.transform = `perspective(1000px) translateY(4px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.01, 1.01, 1.01)`;

        // El formulario solo lleva el efecto 3D normal
        formCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.01, 1.01, 1.01)`;
    });

    area.addEventListener('mouseleave', () => {
        tiltElements.forEach(el => {
            el.style.transition = 'transform 0.5s ease, box-shadow 0.5s ease';
            el.style.boxShadow = '';
        });

        createAccountCard.style.transform = 'perspective(1000px) translateY(0px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        formCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
    });

    area.addEventListener('mouseenter', () => {
        tiltElements.forEach(el => {
            el.style.transition = 'none';
        });
    });
</script>
