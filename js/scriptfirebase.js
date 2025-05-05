
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyCFnOT841ReqwmESV4-GM4aCyWMKCwdX9I",
    authDomain: "smartagro-4e861.firebaseapp.com",
    projectId: "smartagro-4e861",
    storageBucket: "smartagro-4e861.firebasestorage.app",
    messagingSenderId: "700518974771",
    appId: "1:700518974771:web:5c4581c33560a6da17ea46",
    measurementId: "G-R155X3CCDK"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
