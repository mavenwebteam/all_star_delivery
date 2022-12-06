/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts("https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"
);

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyCwxBqRLQX3kQ0vHiNtWmL2VrkgaP3Mc2I",
    authDomain: "all-star-delivery-c143f.firebaseapp.com",
    projectId: "all-star-delivery-c143f",
    storageBucket: "all-star-delivery-c143f.appspot.com",
    messagingSenderId: "706377950722",
    appId: "1:706377950722:web:06d380efa7b7b21f22a91b",
    measurementId: "G-TSRY487E3H",
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    // console.log(
    //     "[firebase-messaging-sw.js] Received background message ",
    //     payload
    // );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});
