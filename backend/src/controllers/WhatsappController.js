import { sendMessage, getQRCode, sendBulkMessage, logoutWhatsApp } from '../services/WhatsappService.js';
import { successResponse, errorResponse } from '../helpers/ResponseHelper.js';
import { body, validationResult } from 'express-validator';

export const generateQRCode = async (req, res) => {
    try {
        console.log('Log : generateQRCode');
        const qrcode = await getQRCode();
        return successResponse(res, 200, 'success', qrcode);
    } catch (error) {
        console.log(error);
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};

export const sendNewMessage = async (req, res) => {
    try {
        console.log('Log : sendNewMessage');
        const { number, message } = req.body;

        await Promise.all(
            [body('number', 'number is required').notEmpty(), body('message', 'message is required').notEmpty()].map((validation) =>
                validation.run(req)
            )
        );

        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            const errorValidation = {};
            errors.array().forEach((error) => {
                errorValidation[error.path] = error.msg;
            });

            return errorResponse(res, 400, 'Error in validation', errorValidation);
        }

        const status = await sendMessage(number, message);
        return successResponse(res, 200, 'Message success sent', status);
    } catch (error) {
        console.log(error);
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};


export const sendNewBulkMessage = async (req, res) => {
    try {
        console.log('Log : sendNewBulkMessage');
        const { bulk } = req.body;

        await Promise.all([body('bulk', 'bulk is required').notEmpty()].map((validation) => validation.run(req)));

        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            const errorValidation = {};
            errors.array().forEach((error) => {
                errorValidation[error.path] = error.msg;
            });

            return errorResponse(res, 400, 'Error in validation', errorValidation);
        }

        console.log(bulk);
        const status = await sendBulkMessage(bulk);
        return successResponse(res, 200, 'Message success sent', status);
    } catch (error) {
        console.log(error);
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};

export const logout = async (req, res) => {
    try {
        console.log('Log : logout');
        const status = await logoutWhatsApp();
        return successResponse(res, 200, 'Logout success', status);
    } catch (error) {
        console.log(error);
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};
