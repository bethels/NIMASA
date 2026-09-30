
<script src="chat_logic.js"></script>

<button id="chat-fab" onclick="toggleChat(true)">
    <span style="font-size: 28px;">💬</span>
</button>

<div id="chat-popup">
    <div id="chat-widget-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 10px; height: 10px; background: #00ff00; border-radius: 50%;"></div>
            <span>NIMASA Support</span>
        </div>
        <button id="close-chat-btn" onclick="toggleChat(false)">✖</button>
    </div>
    
    <div id="chat-widget-body">
        <div id="chat-box">
            </div>
        
        <div class="chat-input-area" id="inputer">
            <input type="text" id="msg-input" placeholder="Type a message..." autocomplete="off">
            <button id="send-btn">➤</button>
        </div>
    </div>
</div>

<style>
    /* Floating Button Styles */
    #chat-fab {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 65px;
        height: 65px;
        background-color: #075e54;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    /* Popup Chat Box Styles (Bottom Left) */
    #chat-popup {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        display: none; /* Hidden initially */
        flex-direction: column;
        z-index: 10000;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    #chat-widget-header {
        background: #075e54;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    #close-chat-btn {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        opacity: 0.8;
    }

    #chat-widget-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #e5ddd5; /* WhatsApp background color */
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
    }

    #chat-box {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Input Styling */
    .chat-input-area {
        padding: 10px;
        background: #f0f0f0;
        display: flex;
        gap: 8px;
        border-top: 1px solid #ddd;
    }

    #msg-input {
        flex: 1;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        outline: none;
    }

    #send-btn {
        background: #075e54;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Message Bubbles */
    .msg { max-width: 80%; padding: 8px 12px; border-radius: 8px; font-size: 13px; position: relative; }
    .right { align-self: flex-end; background: #dcf8c6; border-top-right-radius: 0; }
    .left { align-self: flex-start; background: #fff; border-top-left-radius: 0; }
    .sender-name { font-size: 10px; font-weight: bold; color: #128c7e; display: block; margin-bottom: 2px; }
    .footer { font-size: 9px; margin-top: 4px; display: flex; justify-content: space-between; opacity: 0.6; }
    .del-btn { color: red; background: none; border: none; cursor: pointer; font-size: 10px; }
</style>