export const successResponse = (res, statusCode = 200, message = 'success', data = null) => {
    return res.status(statusCode).json({
        success: true,
        message,
        data,
    });
};

export const errorResponse = (res, statusCode = 400, message = 'error', data = null) => {
    return res.status(statusCode).json({
        success: false,
        message,
        data,
    });
};
