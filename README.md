# PHP Pushover API Wrapper

A simple PHP wrapper for the Pushover API.

## Setup

1. Add your Pushover application token, user key, and usage token in the `config.json` file:

```json
{
    "usageToken": "your-usage-token",
    "apiUrl": "https://api.pushover.net/1/messages.json",
    "token": "your-pushover-app-token",
    "user": "your-pushover-user-key"
}
```

## Usage

You can interact with the API either by making GET or POST requests.

### Minimal Parameters

Send a notification with a message:

Using cURL with GET:

```bash
curl -X GET "https://your-domain.com?usageToken=your-usage-token&action=send&message=Hello%20World"
```

Using cURL with POST:

```bash
curl -X POST -d "message=Hello World" "https://your-domain.com?usageToken=your-usage-token"
```

Replace "your-usage-token" with your actual usage token, "Hello World" with your actual message, and https://your-domain.com with the actual URL.

List of All Possible Parameters
- `message`: Your message
- `attachment`: An image attachment to send with the message
- `device`: Your user's device name to send the message directly to that device, rather than all of the user's devices
- `title`: Your message's title, otherwise your app's name is used
- `url`: A supplementary URL to show with your message
- `url_title`: A title for your supplementary URL, otherwise just the URL is shown
- `priority`: -2 to send no notification/alert, -1 to always send as a quiet notification, 1 to display as high-priority and bypass the user's quiet hours, or 2 to also require confirmation from the user
- `sound`: The name of one of the sounds supported by device clients to override the user's default sound choice. Retrieve the list of all possible sound values by making a GET request without action parameter.

See the official documentation for more details: https://pushover.net/api
