@extends('Layout.app')
@section('title', 'Don Macchiatos')
@include('Components.NavBar.navbar')
@section('content')
    <div
        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f0e3; min-height: 100vh; display: flex; flex-direction: row; justify-content: center; align-items: center;">
        <Sidebar />
        <main style="flex: 1; padding: 20px; display: flex; flex-direction: column; align-items: center;">
            <h1
                style="font-size: 3rem; font-weight: bold; text-align: center; color: #4b3025; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 3px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); padding: 10px 0; border-bottom: 2px solid #d49d6e; display: inline-block; margin-top: 20px;">
                Don Macchiatos</h1>
            <h2 style="font-size: 2rem; color: #4b3025; margin-bottom: 20px; font-weight: bold;">Branch Highlights</h2>
            <div
                style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; width: 95%; max-width: 1600px; justify-content: center; margin: 40px auto;">
                <!-- Branch 1 -->
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/Calambabranch.jpg" alt="Opol Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Grand Opening - Calamba Branch</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        Don Macchiatos Calamba Branch will be reopening its doors and is ready to serve you the best &
                        awarding winning Iced Caramel Macchiato along with five other favorites. And ofcourse, don’t miss
                        the latest flavor addition, our hot drinks series.
                    </p>
                </div>

                <!-- Branch 2 -->
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/branchdasmarinas.png" alt="Dasmarinas Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Dasmarinas Branch</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        Our Dasmariñas branch conducted an outreach program to give back to the community.
                    </p>

                </div>
                <!-- Branch 3 -->
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/branchlingayen.png" alt="Lingayen Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Lingayen Branch</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        Get ready, coffee lovers! Buhisan branch is opening soon!!
                    </p>
                </div>

                <!-- Branch 4 -->
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/branchopol.jpg" alt="Pol Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Opol Branch</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        'Join us in celebrating the grand opening of our Opol branch. Enjoy our 3-for-100 promo!
                    </p>
                </div>
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/branchtabunok.png" alt="Pol Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Tabunok</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        Grand opening celebration with special guest Ariel Alegado. Don't miss out!
                    </p>
                </div>
                <div
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 25px;">
                    <div style="width: 100%; height: 600px; margin-bottom: 20px; position: relative;">
                        <img src="/images/branchbuhisan.png" alt="Pol Branch"
                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h3 style="font-size: 1.6rem; color: #4b3025; margin-bottom: 15px;">Buhisan Branch</h3>
                    <p style="font-size: 1.3rem; color: #4b3025; line-height: 1.5;">
                        Get ready, coffee lovers! Buhisan branch is opening soon!
                    </p>
                </div>
            </div>

        </main>
    </div>
@endsection
